<?php

namespace Tests\Unit;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\Exceptions;


class VerdadeiroOuFalsoTest extends TestCase
{
    /**
     * Um exemplo simples de teste para itentificar um numero maior ou menor que retorne verdadeiro ou falso
     */

    public function test_verdadeiro_ou_falso_numero_maior(): void
    {
        $numero = 4;

        if ($numero > 3) {
           $response = $this->assertTrue(true, "O numero {$numero} e maior que 3");
        } else {
            $response = $this->assertTrue(false, "O numero {$numero} e menor ou igual que 3");
        }
    }

    public function test_verdadeiro_ou_falso_numero_menor(): void
    {
        $numero = 2;

        if ($numero > 3) {
           $response = $this->assertTrue(true, "O numero {$numero} e maior a 3");

           //Retornar o dados do banco, ou cadastrar
           //Redirect para uma rota que precise de authenticacao
        } else {
            $response = $this->assertFalse(false, "O numero {$numero} e menor ou igual a 3");

            //Exception por exemplo
            //Redirect para caso a authenticacao falhar
        }
    }

    public function test_verdadeiro_ou_falso_numero_igual(): void
    {
        $numero = 3;

        if ($numero > 3) {
           $response = $this->assertTrue(true, "O numero {$numero} e maior a 3");
        } else {
            $response = $this->assertFalse(false, "O numero {$numero} e menor ou igual a 3");
        }
    }
}
