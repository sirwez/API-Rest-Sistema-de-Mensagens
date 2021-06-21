<?php
namespace Service;

use Exception;
use Util\ConstantesGenericasUtil;

class usersService
{

    /**
     * @param $userId
     * @return array|string|string[]
     */
    public function getUser($userId)
    {
        $result = self::verificarUser($userId);
        if ($result == false)
        {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['User'] = json_encode($result);
        $response = str_replace("\"", "", $response);
        return $response;
    }

    /**
     * @param $user
     * @return string
     */
    public static function verificarUser($user)
    {
        $id = '';
        try
        {
            $json = file_get_contents(__DIR__ . '\users.json');

            if ($json == false)
            {
                throw new Exception(ConstantesGenericasUtil::MSG_ERRO_AO_ABRIR_ARQUIVO);
            }
            else
            {
                $data = json_decode($json);
                foreach ($data as $key => $value)
                {
                    if (($user == $value->id))
                    {
                        $id = $value->nome;

                    }
                }
                $retorno = $id;
                return $retorno;

            }
        }
        catch(Exception $e)
        {
            exit($e->getMessage());
        }

    }

    /**
     * @param $user
     * @return bool
     */
    public static function isExiste($user)
    {
        $valid = false;
        try
        {
            $json = file_get_contents(__DIR__ . '\users.json');

            if (!$json)
            {
                throw new Exception(ConstantesGenericasUtil::MSG_ERRO_AO_ABRIR_ARQUIVO);
            }
            else
            {
                $data = json_decode($json);
                foreach ($data as $key => $value)
                {
                    if (($user == $value->nome))
                    {
                        $valid = true;
                    }
                }
                if ($valid)
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
        }
        catch(Exception $e)
        {
            $e->getMessage();
        }

    }

    /**
     * @param $user
     * @return false|int
     */
    public function cadastrarUser($user)
    {
        
        try
        {
            $string = file_get_contents(__DIR__ . '\users.json');
            if (!$string)
            {
                throw new Exception(ConstantesGenericasUtil::MSG_ERRO_AO_ABRIR_ARQUIVO);
            }
            else
            {
                $valid = false;
                $json = json_decode($string, true);
                $tamArray = count($json);
                $json[$tamArray]["id"] = $tamArray+1;
                $userValid = $user[0];
                $json[$tamArray]["nome"] = $userValid;
                $fp = fopen(__DIR__ . '\users.json', 'w');
                fwrite($fp, json_encode($json, JSON_PRETTY_PRINT));
                fclose($fp);

                if ($this->isExiste($userValid))
                {
                    $valid = true;
                }
                if ($valid)
                {
                    return $tamArray+1;
                }
                else
                {
                    return false;
                }

            }
        }
        catch(Exception $e)
        {
            echo "Exceção capturada: " . $e->getMessage();
        }

    }

    /**
     * @return array
     */
    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }
}

