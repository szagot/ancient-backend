<?php

namespace Ancient\Service;

use Ancient\Config\Database;
use Ancient\Config\Output;
use Ancient\Control\Crud;
use Ancient\Models\Character;
use Sz\Config\Uri;
use Sz\Conn\Query;

class Characters
{
    public static function run(Uri $uri): void
    {
        $id = (int)$uri->opcao;

        switch ($uri->getMethod()) {
            case 'GET':
                if (empty($id)) {
                    // GET All
                    Output::success(Crud::getAll(Character::class));
                }

                /** @var Character $character */
                $character = Crud::get(Character::class, 'id', $id);
                if (!$character) {
                    Output::error('Personagem não encontrado.', 404);
                }

                // GET /{id}/questions
                if ($uri->detalhe == 'questions') {
                    Output::success($character->getQuestions());
                }

                // GET {id}
                Output::success($character);

                break;

            case 'POST':
                $name = $uri->getParam('name');
                if (empty($name)) {
                    Output::error('Informe o nome do personagem');
                }

                // Nome já cadastrado?
                $character = Crud::search(Character::class, 'name', $name);
                if (count($character) > 0) {
                    Output::success($character[0]);
                }

                if (!$id = Crud::insert(Character::class, 'id', $uri->getParametros())) {
                    Output::error('Não foi possível incluir agora.');
                }

                Output::success(['id' => $id], 201);

                break;

            case 'PUT':
            case 'PATCH':
                if (empty($id)) {
                    Output::error('Informe o ID para atualização.');
                }

                $name = $uri->getParam('name');
                if (empty($name)) {
                    Output::error('Informe o nome do personagem.');
                }

                $character = Crud::get(Character::class, 'id', $id);
                if (!$character) {
                    Output::error('Personagem não encontrado.');
                }

                $character->name = $name;
                if (!Crud::update(Character::class, 'id', $character)) {
                    Output::error('Não foi possível atualizar agora.');
                }

                Output::success([], 204);

                break;

            case 'DELETE':
                if (empty($id)) {
                    Output::error('Informe o ID para deletar.');
                }

                if (!Crud::delete(Character::class, 'id', $id)) {
                    Output::error('Não foi possível excluir agora.');
                }

                Output::success([], 204);
                break;
        }
    }
}