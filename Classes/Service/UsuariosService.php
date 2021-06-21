<?php
namespace Service;

use InvalidArgumentException;
use Repository\UsuariosRepository;
use Util\ConstantesGenericasUtil;
class UsuariosService
{

    public const RECURSOS_GET = ['login', 'emails'];
    public const RECURSOS_DELETE = ['deletar'];
    public const RECURSOS_POST = ['cadastrar', 'enviar', 'encaminhar', 'responder'];
    

    private array $userIDSecret = []; // dois campos, 0 = id, 1 = true/false se o email existe ou não.
    private array $dados;
    private array $dadosCorpoRequest = [];

    private object $UsuariosRepository;
    private object $UsuariosData;

    public function __construct($dados = [])
    {
        $this->dados = $dados;
        $this->UsuariosData = new usersService();
        $this->messagesData = new messagesService();
    }

    /**
     * @param $dadosRequest
     */
    public function setDadosCorpoRequest($dadosRequest)
    {
        $this->dadosCorpoRequest = $dadosRequest;
    }

    /**
     * @return mixed
     */
    public function validarPost()
    {
        $retorno = null;
        $recurso = $this->dados['recurso'];
        
        if (in_array($recurso, self::RECURSOS_POST, true))
        {
            $retorno = $this->$recurso();
        }
        else
        {
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
        }
        if ($retorno == null)
        {
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
        }
        return $retorno;
    }

    /**
     * @return array
     */
    private function responder(){

        $string = file_get_contents(__DIR__ . '\tempUser.json');
        $json = json_decode($string, true);
        $resp[1] =[$this->dadosCorpoRequest['uniqueID']];
        $resp[2] = [$this->dadosCorpoRequest['corpo']];

        $messageRespondida = $this->messagesData->responderMessage( $json['tempID'],$resp);
        if($messageRespondida){
            $response['mensagemRespondida'] = $messageRespondida;
            return $response;
        }
        throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
    }

    /**
     * @return array
     */
    private function encaminhar(){
        $string = file_get_contents(__DIR__ . '\tempUser.json');
        $json = json_decode($string, true);
        $enca[1] = [$this->dadosCorpoRequest['uniqueID']];
        $enca[2] = [$this->dadosCorpoRequest['destinatario']];
        $user=implode("','",$enca[2]);
        if ($this->UsuariosData->isExiste($user)) {
            $messageEncaminhada= $this->messagesData->encaminharMessage( $json['tempID'],$enca);
            if($messageEncaminhada){
                $response['mensagemEncaminhada'] = $messageEncaminhada;
                return $response;
            }
        } else {
            $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
            $response['body'] = 'Destinatário não encontrado';
            return $response;
        }
        
    }

    /**
     * @return array
     */
    private function enviar(){
        

        $string = file_get_contents(__DIR__ . '\tempUser.json');
        $json = json_decode($string, true);
        $msg[1] = [$this->dadosCorpoRequest['remetente']];
        $msg[2] = [$this->dadosCorpoRequest['destinatario']];
        $msg[3] = [$this->dadosCorpoRequest['assunto']];
        $msg[4] = [$this->dadosCorpoRequest['corpo']];
        
        $messageEnviada = $this->messagesData->send_Message( $json['tempID'],$msg);

        if($messageEnviada){
            $response['mensagemEnviada'] = $messageEnviada;
            return $response;
        }
        throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);

    }

    /**
     * @return array
     */
    private function cadastrar()
    {
        
        $login = [$this->dadosCorpoRequest['nome']];
        if ($login)
        {
            if ($this
                ->UsuariosData
                ->isExiste($login))
            {
                throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_LOGIN_EXISTENTE);
            }
            $idInserido = $this
                ->UsuariosData
                ->cadastrarUser($login);
            if ($idInserido)
            {
                $response['user_cadastrado'] = $idInserido;
                return $response;
            }

            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
        }
        throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_LOGIN_SENHA_OBRIGATORIO);
    }


    /**
     * @return array|string|string[]
     */
    public function validarGet()
    {

        $retorno = null;
        $recurso = $this->dados['recurso'];
        if (in_array($recurso, self::RECURSOS_GET, true) && $recurso == 'login')
        {
            // $this->userIDSecret['id'] = $this->dados['id'];
            $retorno = $this->dados['id'] > 0 ? $this
                ->UsuariosData
                ->getUser($this->dados['id']) : $this->$recurso();
                if (array_key_exists('User', $retorno)) {
                    $this->tempID($this->dados['id']);
                }
        }
        elseif (in_array($recurso, self::RECURSOS_GET, true) && $recurso == 'emails')
        {
                $retorno = $this->dados['id'] > 0 ? $this->getOneMessageBykey($this->dados['id']) : $this->$recurso();
        }
        else
        {
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
        }
        if ($retorno == null)
        {
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
        }
        return $retorno;
    }

    /**
     * @param $idUnique
     * @return array
     */
    private function getOneMessageBykey($idUnique)
    {

        return $this
        ->messagesData
        ->getMessage($idUnique);
    }

    /**
     * @return array
     */
    private function emails()
    {
        $string = file_get_contents(__DIR__ . '\tempUser.json');
        $json = json_decode($string, true); 
       return $this
                ->messagesData
                ->listarMessage($json["tempID"]);
    }

    /**
     *
     */
    private function login(){
        throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
    }

    /**
     * @return mixed
     */
    public function validarDelete()
    {
        $retorno = null;
        $recurso = $this->dados['recurso'];
        if (in_array($recurso, self::RECURSOS_DELETE, true))
        {
            if ($this->dados['id'] > 0)
            {
                $retorno = $this->$recurso();
            }
            else
            {
                throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_ID_OBRIGATORIO);
            }
        }
        else
        {
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
        }
        if ($retorno == null)
        {
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
        }
        return $retorno;
    }

    /**
     * @return string
     */
    private function deletar()
    {  
     return $this->messagesData->deleteMessage($this->dados['id']);
    }

    /**
     * @param $id
     */
    private function tempID($id){

        $string = file_get_contents(__DIR__ . '\tempUser.json');
        $json = json_decode($string, true);
        $json["tempID"] = intval($id);
        $fp = fopen(__DIR__ . '\tempUser.json', 'w');
        fwrite($fp, json_encode($json, JSON_PRETTY_PRINT));
        fclose($fp);

    }
}