<?php
    namespace customerapp\src\models;

    use DateTime;

    use customerapp\src\interfaces\Model;

    class Cliente implements Model{
        private int $id = 0;
        private string $nome = "";
        private DateTime $dataNascimento;
        private string $email = "";
        private string $cpf = "";
        private string $senha = "";

        public function __construct() {
        }

        public function getId(): int {
            return $this->id;
        }

        public function setId(int $id) {
            $this->id = $id;
        }

        public function getNome(): string {
            return $this->nome;
        }

        public function setNome(string $nome){
            $this->nome = $nome;
        }

        public function getDataNascimento(): DateTime{
            return $this->dataNascimento;
        }

        public function setDataNascimento(DateTime $dataNascimento){
            $this->dataNascimento = $dataNascimento;
        }

        public function getEmail(): string {
            return $this->email;
        }

        public function setEmail(string $email){
            $this->email = $email;
        }

        public function getCpf(): string {
            return $this->cpf;
        }

        public function setCpf(string $cpf){
            $this->cpf = $cpf;
        }

        public function getSenha(): string {
            return $this->senha;
        }

        public function setSenha(string $senha){
            $this->senha = $senha;
        }

        public function serializar(): array{
            return [
                'id' => $this->id,
                'nome' => $this->nome,
                'data_nascimento' => $this->dataNascimento->format('d/m/Y'),
                'email' => $this->email,
                'cpf' => $this->cpf
            ];
        }
    }