<?php


namespace App\Support;


class CpfCnpjValidacao
{
    /**
     * @var string
     */
    public string $valor;
    /**
     * Valida
     *
     * Valida o CPF ou CNPJ
     *
     * @access public
     * @param string|null $valor
     * @return bool
     */
    public function valida(?string $valor = null): bool
    {
        if($valor){
            $this->valor = $this->apenasNumeros($valor);
        }
        $tipo = $this->verifica_cpf_cnpj();
        if ($tipo === 'CPF') {
            return $this->valida_cpf();
        }
        if ($tipo === 'CNPJ') {
            return $this->valida_cnpj();
        }
        return false;
    }

    /**
     * Verifica se é CPF ou CNPJ
     *
     * Se for CPF tem 11 caracteres, CNPJ tem 14
     *
     * @access protected
     * @return string CPF, CNPJ ou false
     */
    protected function verifica_cpf_cnpj(): string
    {
        // Verifica CPF
        if (strlen($this->valor) === 11) {
            return 'CPF';
        } // Verifica CNPJ
        if (strlen($this->valor) === 14) {
            return 'CNPJ';
        } // Não retorna nada
        return "";
    }

    /**
     * Valida CPF
     *
     * @return bool           True para CPF correto - False para CPF incorreto
     * @author                Luiz Otávio Miranda <contato@todoespacoonline.com/w>
     * @access protected
     */
    protected function valida_cpf()
    {
        // Captura os 9 primeiros dígitos do CPF
        // Ex.: 02546288423 = 025462884
        $digitos = substr($this->valor, 0, 9);

        // Faz o cálculo dos 9 primeiros dígitos do CPF para obter o primeiro dígito
        $novo_cpf = $this->calc_digitos_posicoes($digitos);

        // Faz o cálculo dos 10 dígitos do CPF para obter o último dígito
        $novo_cpf = $this->calc_digitos_posicoes($novo_cpf, 11);

        // Verifica se todos os números são iguais
        if ($this->verifica_igualdade()) {
            return false;
        }

        // Verifica se o novo CPF gerado é idêntico ao CPF enviado
        if ($novo_cpf === $this->valor) {
            // CPF válido
            return true;
        } else {
            // CPF inválido
            return false;
        }
    }

    /**
     * Multiplica dígitos vezes posições
     *
     * @access protected
     * @param string $digitos Os digitos desejados
     * @param int $posicoes A posição que vai iniciar a regressão
     * @param int $soma_digitos A soma das multiplicações entre posições e dígitos
     * @return int|string Os dígitos enviados concatenados com o último dígito
     */
    protected function calc_digitos_posicoes(string $digitos, int $posicoes = 10, int $soma_digitos = 0): int|string
    {
        // Faz a soma dos dígitos com a posição
        // Ex. para 10 posições:
        //   0    2    5    4    6    2    8    8   4
        // x10   x9   x8   x7   x6   x5   x4   x3  x2
        //   0 + 18 + 40 + 28 + 36 + 10 + 32 + 24 + 8 = 196
        for ($i = 0; $i < strlen($digitos); $i++) {
            // Preenche a soma com o dígito vezes a posição
            $soma_digitos = $soma_digitos + ($digitos[$i] * $posicoes);

            // Subtrai 1 da posição
            $posicoes--;

            // Parte específica para CNPJ
            // Ex.: 5-4-3-2-9-8-7-6-5-4-3-2
            if ($posicoes < 2) {
                // Retorno a posição para 9
                $posicoes = 9;
            }
        }

        // Captura o resto da divisão entre $soma_digitos dividido por 11
        // Ex.: 196 % 11 = 9
        $soma_digitos = $soma_digitos % 11;

        // Verifica se $soma_digitos é menor que 2
        if ($soma_digitos < 2) {
            // $soma_digitos agora será zero
            $soma_digitos = 0;
        } else {
            // Se for maior que 2, o resultado é 11 menos $soma_digitos
            // Ex.: 11 - 9 = 2
            // Nosso dígito procurado é 2
            $soma_digitos = 11 - $soma_digitos;
        }

        // Concatena mais um dígito aos primeiro nove dígitos
        // Ex.: 025462884 + 2 = 0254628842
        return $digitos . $soma_digitos;
    }

    /**
     * Verifica se todos os números são iguais
     *     *
     * @access protected
     * @return bool true para todos iguais, false para números que podem ser válidos
     */
    protected function verifica_igualdade(): bool
    {
        // Todos os caracteres em um array
        $caracteres = str_split($this->valor);

        // Considera que todos os números são iguais
        $todos_iguais = true;

        // Primeiro caractere
        $last_val = $caracteres[0];

        // Verifica todos os caracteres para detectar diferença
        foreach ($caracteres as $val) {

            // Se o último valor for diferente do anterior, já temos
            // um número diferente no CPF ou CNPJ
            if ($last_val != $val) {
                $todos_iguais = false;
            }

            // Grava o último número checado
            $last_val = $val;
        }

        // Retorna true para todos os números iguais
        // ou falso para todos os números diferentes
        return $todos_iguais;
    }

    /**
     * Valida CNPJ
     *
     * @return bool             true para CNPJ correto
     * @author                  Luiz Otávio Miranda <contato@todoespacoonline.com/w>
     * @access protected
     */
    protected function valida_cnpj(): bool
    {
        // O valor original
        $cnpj_original = $this->valor;

        // Captura os primeiros 12 números do CNPJ
        $primeiros_numeros_cnpj = substr($this->valor, 0, 12);

        // Faz o primeiro cálculo
        $primeiro_calculo = $this->calc_digitos_posicoes($primeiros_numeros_cnpj, 5);

        // O segundo cálculo é a mesma coisa do primeiro, porém, começa na posição 6
        $segundo_calculo = $this->calc_digitos_posicoes($primeiro_calculo, 6);

        // Concatena o segundo dígito ao CNPJ
        $cnpj = $segundo_calculo;

        // Verifica se todos os números são iguais
        if ($this->verifica_igualdade()) {
            return false;
        }

        // Verifica se o CNPJ gerado é idêntico ao enviado
        if ($cnpj === $cnpj_original) {
            return true;
        }

        return false;
    }

    /**
     * Formata CPF ou CNPJ
     *
     * @access public
     * @return string  CPF ou CNPJ formatado
     */
    public function formata(): string
    {
        // O valor formatado
        $formatado = "";

        // Valida CPF
        if ($this->verifica_cpf_cnpj() === 'CPF') {
            // Verifica se o CPF é válido
            if ($this->valida_cpf()) {
                // Formata o CPF ###.###.###-##
                $formatado = substr($this->valor, 0, 3) . '.';
                $formatado .= substr($this->valor, 3, 3) . '.';
                $formatado .= substr($this->valor, 6, 3) . '-';
                $formatado .= substr($this->valor, 9, 2) . '';
            }
        } // Valida CNPJ
        elseif ($this->verifica_cpf_cnpj() === 'CNPJ') {
            // Verifica se o CPF é válido
            if ($this->valida_cnpj()) {
                // Formata o CNPJ ##.###.###/####-##
                $formatado = substr($this->valor, 0, 2) . '.';
                $formatado .= substr($this->valor, 2, 3) . '.';
                $formatado .= substr($this->valor, 5, 3) . '/';
                $formatado .= substr($this->valor, 8, 4) . '-';
                $formatado .= substr($this->valor, 12, 14) . '';
            }
        }

        // Retorna o valor
        return $formatado;
    }

    /**
     * @param string $valor
     * @return string
     */
    public function apenasNumeros(string $valor): string
    {
        return preg_replace('/[^0-9]/', '', $valor);
    }
}
