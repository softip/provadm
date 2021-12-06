<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Config\Services;

class Produto extends ResourceController
{  
    protected $modelName = 'App\Models\Produto';

    public function index()
    {
        return $this->respond($this->model->findAll());
    }

    public function show($id = null)
    {
        return $this->respond($this->model->find($id));
    }

    public function create()
    {
        $validator = Services::validation();
        $rules = array(
            "nome" => "required",
            "preco" => "required",
            "descricao" => "required"
        );
        $validator->setRules($rules);
        $dados = $this->request->getJSON(true);
        if(!$validator->run($dados)){
            return $this->fail($validator->getErrors());
        }
        $id = $this->model->insert($dados);
        $dados["id"] = $id;
        return $this->respondCreated([
            "status"=>201,
            "mensagem"=> "Produto criado com sucesso",
            "produto" => $dados]);
    }

    public function update($id = null)
    {
        if(!$this->model->find($id)){
            return $this->failNotFound("Esse produto não existe");            
        }
        
        $validator = Services::validation();
        $rules = array(
            "nome" => "required",
            "preco" => "required",
            "descricao" => "required"
        );
        $validator->setRules($rules);
        $dados = $this->request->getJSON(true);
        if(!$validator->run($dados)){
            return $this->fail($validator->getErrors());
        }
        $this->model->update($id, $dados);
        $dados["id"] = $id;
        return $this->respondCreated([
            "status"=>201,
            "mensagem"=> "Produto atualizado com sucesso",
            "produto" => $dados]);
    }

    public function delete($id = null)
    {
        $produto = $this->model->find($id);
        if(!$produto){
            return $this->failNotFound("Produto não existe");
        }else{
            $this->model->delete($id);
            return $this->respond([
                "status" => 200,
                "message" => "Produto Excluído com sucesso",
                "produto" => $produto
            ]);
        }
    }
}
