<?php
namespace Service;

use Exception;
use InvalidArgumentException;
use Util\ConstantesGenericasUtil;

/**
 * Class messagesService
 * @package Service
 */
class messagesService
{

    /**
     * @return array
     */
    private static function verificaMessage()
    {
        try
        {

            $string = file_get_contents(__DIR__ . '\messages.json');
            if (!$string)
            {
                throw new Exception(ConstantesGenericasUtil::MSG_ERRO_AO_ABRIR_ARQUIVO);
            }
            else
            {
                $json = json_decode($string, true);
                $tamArray = count($json);
                $retorno = [$json, $tamArray];
                return $retorno;
            }
        }
        catch(Exception $e)
        {
            $e->getMessage();
        }
    }

    /**
     * @param $id
     * @param $arrayMessage
     * @return array|string
     */
    public static function send_Message($id, $arrayMessage)
    {

        $user = new usersService();
        try
        {
            $remetente = $arrayMessage[1][0];
            $destinatario = $arrayMessage[2][0];
            $assunto = $arrayMessage[3][0];
            $corpo = $arrayMessage[4][0];
            $IDNome = $user->verificarUser($id);
            if ($user->isExiste($IDNome))
            {
                $responde = self::verificaMessage();
                $string = file_get_contents(__DIR__ . '\messages.json');
                $json = json_decode($string, true);
                $tamArray = count($json);

                $responde[0][$responde[1]]["id"] = $id;
                $responde[0][$responde[1]]["uniqueID"] = $tamArray+1;
                $responde[0][$responde[1]]["remetente"] =$remetente;
                $responde[0][$responde[1]]["destinatario"] = $destinatario;
                $responde[0][$responde[1]]["assunto"] = $assunto;
                $responde[0][$responde[1]]["corpo"] = $corpo;
                $responde[0][$responde[1]]["lida"] = false;
                $responde[0][$responde[1]]["resposta"] = false;
                $responde[0][$responde[1]]["encaminhar"] = false;

                $fp = fopen(__DIR__ . '\messages.json', 'w');
                fwrite($fp, json_encode($responde[0], JSON_PRETTY_PRINT));
                fclose($fp);

                $tam = self::verificaMessage();
                if ($tam[1] > $responde[1])
                {
                    return ConstantesGenericasUtil::TIPO_SUCESSO;
                }
                else
                {
                    return ConstantesGenericasUtil::TIPO_ERRO;
                }

            }
            else
            {
                $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
                $response['body'] = 'Destinatário ou Remetente não existem';
                return $response;
            }
        }
        catch(Exception $e)
        {
            $e->getMessage();
        }

    }

    /**
     * @param $id
     * @return array
     */
    public function listarMessage($id)
    {
        $UsuariosData = new usersService();
        $valid = false;
        $json = file_get_contents(__DIR__ . '\users.json');
        $data = json_decode($json);

        foreach ($data as $key => $value)
        {
            if (($id == $value->id))
            {
                $valid = true;
            }
        }

        if ($valid)
        {

            $allMessages[][] = [];
            try
            {
                $string = file_get_contents(__DIR__ . '\messages.json');
                if (!$string)
                {
                    throw new Exception(ConstantesGenericasUtil::MSG_ERRO_AO_ABRIR_ARQUIVO);
                }
                else
                {
                    $json = json_decode($string);
                    $contEnv = 0;
                    $contRec = 0;
                    foreach ($json as $key => $value)
                    {

                            if ($UsuariosData->verificarUser($id) == $value->remetente )
                            {
                                $allMessages['Enviadas'][$contEnv]['remetente'] = $value->remetente;
                                $allMessages['Enviadas'][$contEnv]['destinatario'] = $value->destinatario;
                                $allMessages['Enviadas'][$contEnv]["assunto"] = $value->assunto;
                                $allMessages['Enviadas'][$contEnv]["corpo"] = $value->corpo;

                                $contEnv++;
                            }
                            if ($UsuariosData->verificarUser($id) == $value->destinatario)
                            {
                                $allMessages['Recebidas'][$contRec]['remetente'] = $value->remetente;
                                $allMessages['Recebidas'][$contRec]['destinatario'] = $value->destinatario;
                                $allMessages['Recebidas'][$contRec]["assunto"] = $value->assunto;
                                $allMessages['Recebidas'][$contRec]["corpo"] = $value->corpo;

                                $contRec++;
                            }
                        
                    }
                    unset($allMessages[0]);
                    if (empty($allMessages))
                    {
                        throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERR0_NENHUMA_MSG_ENCONTRADA);
                    }
                    else
                    {
                        return $allMessages;
                    }

                }
            }
            catch(Exception $e)
            {
                $e->getMessage();
            }

        }
        else
        {
            $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
            $response['body'] = null;
            return $response;
        }
    }

    /**
     * @param $idUnique
     * @return array
     */
    public function getMessage($idUnique)
    {
        $oneMessages = [];
        $allMessages = [];
        try
        {

            $string = file_get_contents(__DIR__ . '\messages.json');
            
            if (!$string)
            {
                throw new Exception(ConstantesGenericasUtil::MSG_ERRO_AO_ABRIR_ARQUIVO);
            }
            else
            {
                $json = json_decode($string);
                $cont = 0;
                $string2 = file_get_contents(__DIR__ . '\tempUser.json');
                $json2 = json_decode($string2, true);
                foreach ($json as $key => $value)
                {
                    if (($idUnique == $value->uniqueID) && $json2['tempID'] == $value->id)
                    {
                        $oneMessages[$cont]['remetente'] = $value->remetente;
                        $oneMessages[$cont]['destinatario'] = $value->destinatario;
                        $oneMessages[$cont]["assunto"] = $value->assunto;
                        $oneMessages[$cont]["corpo"] = $value->corpo;
                        $cont++;
                    }

                }
                if (empty($oneMessages))
                {
                    throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERR0_NENHUMA_MSG_ENCONTRADA);
                }
                else
                {

                    $json = json_decode($string);
                    $cont = 0;
                    foreach ($json as $key => $value)
                    {
                        if (($idUnique  == $value->uniqueID))
                        {
                            $allMessages[$cont]['id'] = $value->id;
                            $allMessages[$cont]['uniqueID'] = $value->uniqueID;
                            $allMessages[$cont]['remetente'] = $value->remetente;
                            $allMessages[$cont]['destinatario'] = $value->destinatario;
                            $allMessages[$cont]['assunto'] = $value->assunto;
                            $allMessages[$cont]['corpo'] = $value->corpo;
                            $allMessages[$cont]['lida'] = true;
                            $allMessages[$cont]['resposta'] = $value->resposta;
                            $allMessages[$cont]['encaminhada'] = $value->encaminhada;

                        }
                        else
                        {
                            $allMessages[$cont]['id'] = $value->id;
                            $allMessages[$cont]['uniqueID'] = $value->uniqueID;
                            $allMessages[$cont]['remetente'] = $value->remetente;
                            $allMessages[$cont]['destinatario'] = $value->destinatario;
                            $allMessages[$cont]['assunto'] = $value->assunto;
                            $allMessages[$cont]['corpo'] = $value->corpo;
                            $allMessages[$cont]['lida'] = $value->lida;
                            $allMessages[$cont]['resposta'] = $value->resposta;
                            $allMessages[$cont]['encaminhada'] = $value->encaminhada;

                        }
                        $cont++;

                    }
                    $fp = fopen(__DIR__ . '\messages.json', 'w');
                    fwrite($fp, json_encode($allMessages, JSON_PRETTY_PRINT));
                    fclose($fp);
                    return $oneMessages;
                }
                
            }
        }
        catch(Exception $e)
        {
            $e->getMessage();
        }

    }

    /**
     * @param $id
     * @param $arrayMessage
     * @return string
     */
    public function encaminharMessage($id, $arrayMessage)
    {
        $IdUnic = $arrayMessage[1][0];
        $dest = $arrayMessage[2][0];
        try
        {
            if (true)
            {
                $responde = self::verificaMessage();
                $string = file_get_contents(__DIR__ . '\messages.json');
                $json2 = json_decode($string, true);
                $tamArray = count($json2);
                foreach ($json2 as $key => $value) {

                    if($IdUnic==$value['uniqueID']){
                        $responde[0][$responde[1]]["id"] = $id;
                        $responde[0][$responde[1]]["uniqueID"] = $tamArray+1;
                        $responde[0][$responde[1]]["remetente"] = $value['remetente'];
                        $responde[0][$responde[1]]["destinatario"] = $dest;
                        $responde[0][$responde[1]]["assunto"] = $value['assunto'];
                        $responde[0][$responde[1]]["corpo"] = $value['corpo'];
                        $responde[0][$responde[1]]["lida"] = false;
                        $responde[0][$responde[1]]["resposta"] = false;
                        $responde[0][$responde[1]]["encaminhar"] = true;
                    }
                }

                $fp = fopen(__DIR__ . '\messages.json', 'w');

                fwrite($fp, json_encode($responde[0], JSON_PRETTY_PRINT));
                fclose($fp);

                $tam = self::verificaMessage();
                if ($tam[1] > $responde[1])
                {
                    return ConstantesGenericasUtil::TIPO_SUCESSO;
                }
                else
                {
                    return ConstantesGenericasUtil::TIPO_ERRO;
                }

            }
            else
            {
                $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
                $response['body'] = 'Destinatário ou Remetente não existem';
                return $response;
            }
        }
        catch(Exception $e)
        {
            $e->getMessage();
        }

    }

    /**
     * @param $idUnique
     * @return string
     */
    public function deleteMessage($idUnique)
    {
        $allMessages = [];
        try
        {
            $string = file_get_contents(__DIR__ . '\messages.json');
            if (!$string)
            {
                throw new Exception(ConstantesGenericasUtil::MSG_ERRO_AO_ABRIR_ARQUIVO);
            }
            else
            {
                $json = json_decode($string);
                $tamAntes = self::verificaMessage();
                $cont = 0;
                foreach ($json as $key => $value)
                {
                    if (($idUnique == $value->uniqueID))
                    {
                        unset($allMessages[$cont]);
                    }
                    else
                    {
                        $allMessages[$cont]['id'] = $value->id;
                        $allMessages[$cont]['uniqueID'] = $value->uniqueID;
                        $allMessages[$cont]['remetente'] = $value->remetente;
                        $allMessages[$cont]['destinatario'] = $value->destinatario;
                        $allMessages[$cont]['assunto'] = $value->assunto;
                        $allMessages[$cont]['corpo'] = $value->corpo;
                        $allMessages[$cont]['lida'] = $value->lida;
                        $allMessages[$cont]['resposta'] = $value->resposta;
                        $allMessages[$cont]['encaminhada'] = $value->encaminhada;
                        $cont++;
                    }

                }
                $fp = fopen(__DIR__ . '\messages.json', 'w');
                fwrite($fp, json_encode($allMessages, JSON_PRETTY_PRINT));
                fclose($fp);
                
                $json2 = json_decode($string);
                $isDeleted = false;
                foreach ($json2 as $key => $value)
                {
                    if (($idUnique == $value->uniqueID))
                    {
                        $isDeleted = true;
                    }
                    else
                    {
                        continue;
                    }

                }
                if ($isDeleted)
                {
                    return ConstantesGenericasUtil::MSG_DELETADO_SUCESSO;
                }
                else
                {
                    return ConstantesGenericasUtil::MSG_ERRO_GENERICO;
                }

            }
        }
        catch(Exception $e)
        {
            $e->getMessage();
        }
    }

    /**
     * @param $id
     * @param $arrayMessage
     * @return string
     */
    public function responderMessage($id, $arrayMessage)
    {
        $IdUnic = $arrayMessage[1][0];
        $corpo = $arrayMessage[2][0];
        try
        {
            if (true)
            {
                $responde = self::verificaMessage();
                $string = file_get_contents(__DIR__ . '\messages.json');
                $json2 = json_decode($string, true);
                $tamArray = count($json2);
                $userSearch = new usersService();
                $newRemetente = $userSearch->verificarUser($id);
                foreach ($json2 as $key => $value) {

                    if($IdUnic==$value['uniqueID']){
                        $responde[0][$responde[1]]["id"] = $id;
                        $responde[0][$responde[1]]["uniqueID"] = $tamArray+1;
                        $responde[0][$responde[1]]["remetente"] = $newRemetente;
                        $responde[0][$responde[1]]["destinatario"] = $value['remetente'];
                        $responde[0][$responde[1]]["assunto"] = $value['assunto'];
                        $responde[0][$responde[1]]["corpo"] = $corpo;
                        $responde[0][$responde[1]]["lida"] = false;
                        $responde[0][$responde[1]]["resposta"] = true;
                        $responde[0][$responde[1]]["encaminhar"] = false;
                    }
                }

                $fp = fopen(__DIR__ . '\messages.json', 'w');

                fwrite($fp, json_encode($responde[0], JSON_PRETTY_PRINT));
                fclose($fp);

                $tam = self::verificaMessage();
                if ($tam[1] > $responde[1])
                {
                    return ConstantesGenericasUtil::TIPO_SUCESSO;
                }
                else
                {
                    return ConstantesGenericasUtil::TIPO_ERRO;
                }

            }
            else
            {
                $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
                $response['body'] = 'Destinatário ou Remetente não existem';
                return $response;
            }
        }
        catch(Exception $e)
        {
            $e->getMessage();
        }
    }

}

