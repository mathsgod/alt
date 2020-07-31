<?php

use R\GraphQL\Schema;

class api_index extends R\Page
{
    public function get()
    {
        $this->write("api");
    }

    public function post()
    {
        $loader = new \Composer\Autoload\ClassLoader();
        $loader->addPsr4("Type\\", __DIR__ . "/Type");
        $loader->register();
        $schema = Schema::Build(file_get_contents(__DIR__ . "/schema.gql"), $this->app);


        header('Content-Type: application/json');

        $input = $this->request->getBody()->getContents();
        $input = json_decode($input, true);

        $query = $input['query'];
        $variableValues = $input['variables'];

        $result = $schema->executeQuery($query, $variableValues);

        $this->write(json_encode($result));
    }
}
