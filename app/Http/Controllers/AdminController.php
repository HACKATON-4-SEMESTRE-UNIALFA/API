<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{

    public function index()
    {
        $admin = Usuario::where('isAdmin', 1)->get();

        if ($admin->isEmpty()) {
            return response()->json([
                'error' => true,
                'message' => 'Nenhum Admin encontrado!',
            ], 404);
        }

        return response()->json([
            'error' => false,
            'message' => 'Lista de Administradores',
            'admins' => $admin,
        ], 200);
    }

    /**
     * Constroi um admin com base na classe de usuario e depois atualiza a informacao
     * do usuario cadastrado para admin, dessa forma authenticando o usuario como admin
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


        $admin = Usuario::create([
            'nome' => $request->input('nome'),
            'email' => $request->input('email'),
            'senha' => bcrypt($request->input('senha')),
            'telefone' => $request->input('telefone'),
            'cpf' => $request->input('cpf'),
            'isAdmin' => true,
            'isUser' => true
        ]);

        return response()->json([
            'error' => false,
            'message' => 'Administrador criado com sucesso!',
            'admin' => $admin,
        ], 201);
    }


    /**
     * Lista usuario pelo id apenas se for admin
     */
    public function show($id)
    {
        $admin = Usuario::where('id', $id)->where('isAdmin', 1)->first();

        if (!$admin) {
            return response()->json([
                'error' => true,
                'message' => 'Admin nao encontrado!',
            ], 404);
        }

        return response()->json([
            'error' => false,
            'message' => 'Lista de Administradores',
            'admin' => $admin,
        ], 200);
    }

    /**
     * edita apenas o admin
     */
    public function update(Request $request, $id)
    {

        $validador = Validator::make(
            $request->all(),
            [
                'nome' => 'required|string|min:8',
                'email' => [
                    'required',
                    'email',
                    'unique:usuarios,email,' . $id,
                    function ($attribute, $value, $fail) use ($id) {
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
                    'nullable',
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
                'confirmaSenha' => 'nullable|same:senha',
                'telefone' => 'required|string|max:15',
            ],
            [
                'required' => 'O campo :attribute é obrigatório',
                'string' => 'O campo :attribute deve ser string',
                'regex' => 'O campo :attribute deve conter caracteres Especiáis, Maiúsculos, Minusculos, Numeros e no minimo 7 caracteres',
                'email.unique' => 'O email informado já está cadastrado.',
                'email.email' => 'O email informado deve estar no formato correto.',
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


        $admin = Usuario::find($id);
        if(!$admin){
            return response()->json([
                'error' => true,
                'message' => 'Admin nao encontrado',
            ], 404);
        }

        $admin->update([
            'nome' => $request->nome,
            'email' => $request->email,
            'senha' => bcrypt($request->senha),
            'telefone' => $request->telefone,
            'cpf' => $request->cpf,
            'isAdmin' => true,
            'isUser' => true
        ]);

        return response()->json([
            'error' => false,
            'message' => 'Administrador editado com sucesso!',
            'admin' => $admin,
        ], 201);

    }

    /**
     * Remove permissao de adm
     */
    public function desabilita($id)
    {
        $admin = Usuario::find($id);
        
        if(!$admin){
            return response()->json([
                'error' => true,
                'message' => 'Admin nao encontrado!',
            ], 404);
        }

        $admin->update([
            'isAdmin' => false,
        ]);
        return response()->json([
            'error' => false,
            'message' => 'Admin desabilitado',
            'Dados editados' => [$admin]
        ], 200);
    }

    /**
     * concede permissao de adm
     */
    public function habilita($id)
    {
        $admin = Usuario::find($id);
        
        if(!$admin){
            return response()->json([
                'error' => true,
                'message' => 'Admin nao encontrado!',
            ], 404);
        }

        $admin->update([
            'isAdmin' => true,
        ]);
        return response()->json([
            'error' => false,
            'message' => 'Admin habilitado',
            'Dados editados' => $admin
        ], 200);
    }

     /**
     * Deleta admin
     */
    public function destroy($id)
    {
        $admin = Usuario::find($id);
        
        if(!$admin){
            return response()->json([
                'error' => true,
                'message' => 'Admin nao encontrado!',
            ], 404);
        }

        $admin->delete();


        return response()->json([
            'error' => false,
            'message' => 'Admin nao encontrado',
            'Dados deletados' => [$admin]
        ], 200);
    }
}

