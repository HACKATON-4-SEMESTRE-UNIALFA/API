<?php

namespace App\Http\Controllers;

use App\Models\usuario;
use App\Models\Usuario as ModelsUsuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsuarioController extends Controller
{


    /**
     * Verifica se o email passado na request existe no banco
     * apos verificado compara a senha do banco com a senha da
     * request, se todos os valores estiverem certos gera o token
     */
    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'senha' => 'required',
        ]);

        $usuario = Usuario::where('email', $request->email)->first();


        if (!$usuario || !Hash::check($request->senha, $usuario->senha)) {
            return response()->json([
                'error' => true,
                'message' => 'Credenciais inválidas'
            ], 401);
        }



        $token = $usuario->createToken('auth_token')->plainTextToken;
        return response()->json([
            'error' => false,
            'message' => 'Login realizado com sucesso',
            'accessToken' => $token,
            'usuario' => $usuario
        ], 200);
    }


    /**
     *  Buscar todos o usuários
     */
    public function index()
    {
        $usuarios = Usuario::all();
        if ($usuarios->isEmpty()) {
            return response()->json([
                'error' => true,
                "message" => "Nenhum usuario encontrado"
            ], 404);
        }
        return response()->json([
            'error' => false,
            'Dados dos Usuarios' => [$usuarios]
        ], 200);
    }


    /**
     * Tradando dados da requsição com o Validator antes de salvar no banco de dados.
     * Passando todos os dados da requisisao e tratando individualmente cada um dos dados,
     * além de entregar na resposta mensagens personalizadas de cada um dos possiveis erros no campo
     */
    public function store(Request $request)
    {
        //

        $validador = Validator::make(
            $request->all(),
            [
                'nome' => 'required|string|min:8',
                'email' => [
                    'required',
                    'email',
                    'unique:usuarios',
                    function ($attribute, $value, $fail) {
                        // Verifica se o e-mail contém espaços em branco
                        if (strpos($value, ' ') !== false) {
                            $fail('O campo :attribute não pode conter espaços em branco.');
                        }
                        // Verifica se o e-mail contém o símbolo '@'
                        if (strpos($value, '@') === false) {
                            $fail('O campo :attribute deve conter o símbolo @.');
                        }
                        // Verifica se o e-mail contém pelo menos um ponto '.'
                        if (strpos($value, '.') === false) {
                            $fail('O campo :attribute deve conter um ponto (.)');
                        }
                    }
                ],
                'senha' => [
                    'required',
                    'string',
                    'min:7',
                    function ($attribute, $value, $fail) {
                        // Verifica maiusculas
                        if (!preg_match('/[A-Z]/', $value)) {
                            $fail("A senha deve conter pelo menos uma letra maiúscula.");
                        }
                        // Verifica minusculas
                        if (!preg_match('/[a-z]/', $value)) {
                            $fail("A senha deve conter pelo menos uma letra minúscula.");
                        }
                        // Verifica os numericos
                        if (!preg_match('/\d/', $value)) {
                            $fail("A senha deve conter pelo menos um número.");
                        }
                        // Verifica caracteres especiais
                        if (!preg_match('/[@$!%*?&]/', $value)) {
                            $fail("A senha deve conter pelo menos um caractere especial.");
                        }
                    }
                ],
                'confirmaSenha' => 'required|same:senha',
                'telefone' => 'required|string|max:15',
                'cpf' => 'required|string|size:11|unique:usuarios,cpf',
            ],
            [
                'required' => 'O campo :attribute é obrigatório',
                'string' => 'O campo :attribute deve ser string',
                'regex' => 'O campo :attribute deve conter caracteres Especiáis, Maiúsculos, Minusculos, Numeros e no minimo 7 caracteres',
                'email.unique' => 'O email informado já está cadastrado.',
                'email.email' => 'O email informado deve estar no formato correto.',
                'cpf.unique' => 'O CPF informado já está cadastrado.',
                'cpf.size' => 'O Nome deve conter 11 caracteres no total',
                'confirmaSenha.same' => 'As senhas devem ser idênticas',
                'senha.min' => 'As senhas devem conter no minimo 7 caracteres',
                'nome.min' => 'O nome deve conter no minimo 8 caracteres',
                'telefone.max' => 'Telefone de conter no maximo 15 caracteres',
            ],
            [
                'nome' => 'Nome',
                'email' => 'Email',
                'senha' => 'Senha',
                'confirmaSenha' => 'Confirma Senha',
                'telefone' => 'Telefone',
                'cpf' => 'CPF'
            ],
            422
        );


        if ($validador->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Erro na validação dos dados.',
                'errors' => $validador->errors(),
            ], 422);
        }


        $usuario = Usuario::create([
            'nome' => $request->input('nome'),
            'email' => $request->input('email'),
            'senha' => bcrypt($request->input('senha')),
            'telefone' => $request->input('telefone'),
            'cpf' => $request->input('cpf'),
            'isUser' => true
        ]);

        return response()->json([
            'error' => false,
            'message' => 'Usuário criado com sucesso!',
            'usuario' => $usuario,
        ], 201);
    }


    /**
     * Filtrar usuário pelo ID
     */
    public function show($id)
    {
        $usuario = Usuario::find($id);
        if (!$usuario) {
            return response()->json([
                'error' => true,
                'message' => 'Usuario não encontrado'
            ], 404);
        }
        return response()->json([
            'error' => false,
            'usuario' => $usuario
        ], 200);
    }


    /**
     * Edita um usuário especifico com o Validator, passando todos os dados da requisisao
     * e tratando individualmente cada um dos dados, além de entregar na resposta mensagens
     * personalizadas de cada um dos possiveis erros no campo
     */
    public function update(Request $request, $id)
    {
        $validador = Validator::make(
            $request->all(),
            [
                'nome' => 'required|string|min:8',
                'email' => [
                    'nullable',
                    'email',
                    'unique:usuarios,email,' . $id,
                    function ($attribute, $value, $fail) use ($id) {
                        // Verifica se o e-mail contém espaços
                        if (strpos($value, ' ') !== false) {
                            $fail('O campo :attribute não pode conter espaços em branco.');
                        }
                        // Verifica se o e-mail contém o @
                        if (strpos($value, '@') === false) {
                            $fail('O campo :attribute deve conter o símbolo @.');
                        }
                        // Verifica se o e-mail contém pelo menos um ponto
                        if (strpos($value, '.') === false) {
                            $fail('O campo :attribute deve conter um ponto (.)');
                        }
                    }
                ],
                'senha' => [
                    'nullable', // Senha não é obrigatória no update, a menos que seja fornecida
                    'string',
                    'min:7',
                    function ($attribute, $value, $fail) {
                        if ($value) { // Só valida se a senha foi fornecida
                            // Verifica maiúsculas
                            if (!preg_match('/[A-Z]/', $value)) {
                                $fail("A senha deve conter pelo menos uma letra maiúscula.");
                            }
                            // Verifica minúsculas
                            if (!preg_match('/[a-z]/', $value)) {
                                $fail("A senha deve conter pelo menos uma letra minúscula.");
                            }
                            // Verifica os números
                            if (!preg_match('/\d/', $value)) {
                                $fail("A senha deve conter pelo menos um número.");
                            }
                            // Verifica caracteres especiais
                            if (!preg_match('/[@$!%*?&]/', $value)) {
                                $fail("A senha deve conter pelo menos um caractere especial.");
                            }
                        }
                    }
                ],
                'confirmaSenha' => 'nullable|same:senha',
                'telefone' => 'required|string|max:15',
            ],
            [
                'required' => 'O campo :attribute é obrigatório',
                'string' => 'O campo :attribute deve ser string',
                'email.unique' => 'O email informado já está cadastrado.',
                'email.email' => 'O email informado deve estar no formato correto.',
                'confirmaSenha.same' => 'As senhas devem ser idênticas.',
                'senha.min' => 'A senha deve conter no mínimo 7 caracteres.',
                'telefone.max' => 'O telefone deve conter no máximo 15 caracteres.',
            ],
            [
                'nome' => 'Nome',
                'email' => 'Email',
                'senha' => 'Senha',
                'confirmaSenha' => 'Confirma Senha',
                'telefone' => 'Telefone',
            ]
        );

        if ($validador->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validador->errors()->all()
            ], 422);
        }

        // Encontre o usuário pelo ID
        $usuario = Usuario::find($id);
        if (!$usuario) {
            return response()->json([
                'error' => true,
                'message' => 'Usuário não encontrado.'
            ], 404);
        }

        // Atualize os dados do usuário
        $usuario->update([
            'nome' => $request->nome,
            'email' => $request->email,
            'senha' => $request->senha ? bcrypt($request->senha) : $usuario->senha,
            'telefone' => $request->telefone
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Usuário atualizado com sucesso.',
            'data' => $usuario
        ], 200);
    }


    /**
     * Deleta o usuário pelo id
     */
    public function destroy($id)
    {
        $usuario = Usuario::find($id);
        if (!$usuario) {
            return response()->json([
                'error' => true,
                'message' => 'Usuário não encontrado!'
            ], 404);
        }

        $usuario->delete();
        return response()->json([
            'error' => false,
            'message' => 'Usuario deletado com sucesso',
            'Dados deletados' => [$usuario]
        ], 200);
    }
}
