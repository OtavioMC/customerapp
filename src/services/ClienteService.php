<?php

    namespace customerapp\src\services;

    use customerapp\src\models\Cliente;

    use customerapp\src\exceptions\ServiceException;
    use \DateTime;

    class ClienteService extends ServiceGeral {

        private static $instancia = null;
        private const PESO_10_CPF = 10;
        private const PESO_11_CPF = 11;

        public static function getInstancia() {
            if (self::$instancia === null) {
                self::$instancia = new self();
            }
            return self::$instancia;
        }

        public function validar($cliente, array &$erros = [] ) {
            $mensagemErro = "";
            try{
                if (!$cliente instanceof Cliente) {
                    $erros['Cliente'] = "Não é possível realizar cadastro vazio.";
                    throw new ServiceException("O objeto fornecido não é uma instância válida de Cliente");
                }

                $this->validarNome($cliente->getNome(), $erros);
                $this->validarDataNascimento($cliente->getDataNascimento(), $erros);
                $this->validarEmail($cliente->getEmail(), $erros);
                $this->validarCpf($cliente->getCpf(), $erros);

                if( $cliente->getId() == 0 || ! empty ($cliente->getSenha() ) ){
                    $this->validarSenha($cliente->getSenha(), $erros);
                }

                if( ! empty( $erros ) ){

                    foreach($erros as $campo => $erro){
                        $mensagemErro .= " O(a) " . $campo  . " possui o seguinte erro:" . $erro;
                    }

                    throw new ServiceException( $mensagemErro );

                }
            }catch(ServiceException $e){
                throw new ServiceException( $e->getMessage() );
            }
            return true;
        }

        private function validarNome($nome, &$erros ) {
            if (empty($nome)) {
                $erros['nome'] = "O nome não pode estar vazio!";
            }

            if( mb_strlen($nome) < 6 ){
                $erros['nome'] = "O nome não pode ter menos que 6 caracteres!";
            }

            if( ! mb_strpos( $nome, " ") ){
                $erros['nome'] = "O nome está incompleto, por favor complete com o sobrenome.";
            }

        }

        private function validarDataNascimento( $dataNascimento , &$erros ) {
            $dataAtual = new DateTime('now');
            if (  $dataNascimento === false  || $dataNascimento > $dataAtual ){
                $erros["data de nascimento"] = "Data de nascimento inválida!";
            }
        }

        private function validarEmail( $email, &$erros ) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $erros["email"] = "Email inválido : " . $email;
            }
        }

        private function validarCpf( $cpf, &$erros ) {

            if (! $this->validarFormatacaoCpf($cpf) || ! $this->validarDigitosVerificadoresCpf( $cpf ) ) {
                $erros['cpf'] = "CPF inválido: " . $cpf;
            }
        }

        private function validarFormatacaoCpf( $cpf ){
            return  preg_match("/^[0-9]{3}\.[0-9]{3}\.[0-9]{3}\-[0-9]{2}$/", $cpf);
        }

        private function limpaFormatacaoCpf(string $cpf): string{
            return str_replace(['.','-'],"",$cpf);
        }

        private function validarDigitosVerificadoresCpf( $cpf ){
            //Preparando o CPF com 9 dígitos
            $cpfSemFormatacao = $this->limpaFormatacaoCpf($cpf);

            $cpfCom9PrimeirosDigitos = substr($cpfSemFormatacao,0,9);

            // 1º Passo:Calculando o primeiro dígito verificador

            $primeiroDigitoVerificador = $this->calculaDigitoVerificadorCpf($cpfCom9PrimeirosDigitos, self::PESO_10_CPF);

            // 2º Passo: Calculando o segundo dígito verificador

            $cpfCom10PrimeirosDigitos = $cpfCom9PrimeirosDigitos . $primeiroDigitoVerificador;

            $segundoDigitoVerificador = $this->calculaDigitoVerificadorCpf($cpfCom10PrimeirosDigitos, self::PESO_11_CPF);

            /*3º Passo: Comprar se os 2 dígitos verificadores encontrados são iguais
            aos dígitos verificadores do CPF analisado. Se forem iguais, então o CPF é válido.*/

            $cpfAposValidacao = $cpfCom9PrimeirosDigitos . $primeiroDigitoVerificador . $segundoDigitoVerificador;

            if ($cpfSemFormatacao != $cpfAposValidacao){
                return false;
            }

            return true;
        }


        private function calculaDigitoVerificadorCpf( string $numeroCpf, int $pesoMultiplicadores ){

            //Prepara o cpf e transforma ele em Array para realizar as multiplicações
            $cpfArray = str_split($numeroCpf);
            $tamanhoCpf = count($cpfArray);

            //1º Passo: Realizar a multiplicação dos dígitos do CPF e de uma sequência de pesos associados a cada um deles. O resultado de cada multiplicação é somado:

            for ($i = 0; $i < $tamanhoCpf; $i++){
                $resultadoMultiplicacao[$i]  = (int)$cpfArray[$i] * $pesoMultiplicadores;
                $pesoMultiplicadores--;
            }

            $somaDoCpf = ! empty( $resultadoMultiplicacao ) ? array_sum($resultadoMultiplicacao) : 0;

            // 2º Passo: O resultado da soma das multiplicações é dividido por 11, com o propósito de obter o resto da divisão:

            $restoDaDivisao = $somaDoCpf % 11;

            //3º Passo:  Se o resto da divisão for menor que 2, logo o primeiro dígito verificador é 0; caso contrário, o primeiro dígito verificador é obtido através da subtração de 11 menos o resto da divisão;

            if ($restoDaDivisao < 2){
                return 0;
            }

            $resultadoSubtracao = 11 - $restoDaDivisao;

            return $resultadoSubtracao;
        }

        private function validarSenha($senha, &$erros) {
            if (strlen($senha) < 6) {
                $erros["senha"] = "A senha deve ter no mínimo 6 caracteres.";
            }
            if (strlen($senha) > 20) {
                $erros["senha"] = "A senha deve ter no máximo 20 caracteres.";
            }
        }
    }