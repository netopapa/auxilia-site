<?php
// API
ini_set('display_errors', 1);
ini_set('display_startup_erros', 1);
error_reporting(E_ALL);

//SERVIDOR DE E-MAIL
define("MAILUSER", "nao-responder@auxiliatech.com.br");
define("MAILPASS", "BEBEzinho2019@");
define("MAILPORT", "587");
define("MAILHOST", "mail.auxiliatech.com.br");

//E-MAIL UTILIZADO NO PROJETO
define("EMAILSUPORTE", "contato@auxiliatech.com.br");
define("EMAILATENDIMENTO", "contato@auxiliatech.com.br");
define("EMAILVENDAS", "contato@auxiliatech.com.br");
define("EMAILDENUNCIA", "contato@auxiliatech.com.br");

$dataHora = date('d/m/Y - H:i:s');

function sendMail($assunto, $mensagem, $remetente, $nomeRemetente, $destino, $nomeDestino, $reply = NULL, $replyNome = NULL, $anexo_pasta = NULL)
{

    require_once('PHPMailer/PHPMailerAutoload.php'); //Include pasta/classe do PHPMailer

    $mail = new PHPMailer(); //INICIA A CLASSE
    try {
        $mail->IsSMTP(); //Habilita envio SMPT
        $mail->SMTPAuth = true; //Ativa email autenticado
        $mail->IsHTML(true);

        $mail->Host = '' . MAILHOST . ''; //Servidor de envio
        $mail->Port = '' . MAILPORT . ''; //Porta de envio
        $mail->Username = '' . MAILUSER . ''; //email para smtp autenticado
        $mail->Password = '' . MAILPASS . ''; //seleciona a porta de envio

        $mail->From = utf8_decode($remetente); //remtente
        $mail->FromName = utf8_decode($nomeRemetente); //remtetene nome

        if ($anexo_pasta != NULL) {
            $mail->AddAttachment($anexo_pasta); //Enviar anexo
        }

        if ($reply != NULL) {
            $mail->AddReplyTo(utf8_decode($reply), utf8_decode($replyNome));
        }

        $mail->Subject = utf8_decode($assunto); //assunto
        $mail->Body = utf8_decode($mensagem); //mensagem
        $mail->AddAddress(utf8_decode($destino), utf8_decode($nomeDestino)); //email e nome do destino

        $aa = $mail->Send();
        if ($aa) {
            echo 'ok';
        } else {
            echo $mail->ErrorInfo;
        }
    } catch (phpmailerException $e) {
        echo $e->errorMessage(); //Pretty error messages from PHPMailer
    } catch (Exception $e) {
        echo $e->getMessage(); //Boring error messages from anything else!
    }
    return false;
}

$post = file_get_contents("php://input");
$c['nome'] = $_POST['name'];
$c['assunto'] = $_POST['subject'];
$c['email'] = $_POST['email'];
$c['tel'] = $_POST['tel'];
$txt = $_POST['message'];


if (in_array('', $c)) {
    echo 'Existem campos em branco.';
} else {

    $c['mensagem'] = '
                        <!DOCTYPE html>
                            <html lang="pt-br">
                                <head>
                                    <meta charset="UTF-8">
                                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                                    <meta name="viewport" content="width=device-width, initial-scale=1"/>
                                    <title>E-mail</title>
                                </head>
                                <body>
                                    <style type="text/css">
                                        body{background:#f0f0f0;}
                                    </style>
                                <center>
                                    <img src="imagens_site/logo.png" title="Casa dos site" alt="Logo" width="400">
                                    <br>
                                </center>
                                    <div style="width: 70%; margin-left: 15%; padding: 15px; background-color: #fff; float: left">
                                        <h1 style=" margin-top: 0; font-size: 1.3em;">Olá!</h1>
                                        <p>O usuário ' . $c['nome'] . ', enviou pelo formulário de interesse do site as seguintes informações:</p>
                                        <p>E-mail: ' . $c['email'] . '</p>
                                        <p>Assunto: ' . $c['assunto'] . '</p>
                                        <p>Telefone: ' . $c['tel'] . '</p>
                                        <p>Texto: ' . $txt . '</p>
                                        <p>Foi enviado em: ' . $dataHora . '</p>
                                    </div>
                                <div style="clear: both !important;"></div>
                                    <p style="text-align: center; color: #848484; font-size: 0.8em; margin-bottom: 5px">' . date('Y') . ' - </p>
                                    <p style="text-align: center; color: #848484; font-size: 0.8em; margin-bottom: 5px">
                                    <a href="" target="_blank" style=" color: #848484;" title=""></a>
                                    </p>
                                    <p style="text-align: center; color: #848484; font-size: 0.8em; margin-bottom: 5px">E-mail enviado em: ' . $dataHora . '</p>
                                    <div style="clear: both !important;"></div>
                                </body>
                            </html>
                            ';

    // USUÁRIO RECEBDO E-MAIL PARA CONFIRMAÇÃO
    $email_senha = array(
        "Assunto" => "Formulário de contato", // Assunto do e-mail.
        "Mensagem" => $c['mensagem'], //Mensagem do e-mail pode ser em html.
        "RemetenteNome" => $c['nome'], //Nome da pessoa que enviou.
        "RemetenteEmail" => $c['email'], //E-mail da pessoa que enviou.
        "DestinoNone" => "Auxilia", //Nome da pessoa que vai receber.
        "DestinoEmail" => EMAILATENDIMENTO //Email da pessoa que esta recebendo.
    );

    // $enviar_envio = sendMail($email_senha['Assunto'], $c['mensagem'], $email_senha['RemetenteEmail'], $email_senha['RemetenteNome'], $email_senha['DestinoEmail'], $email_senha['DestinoNone'], $email_senha['RemetenteEmail'], $replyNome = NULL, $anexo_pasta = NULL);
    sendMail($email_senha['Assunto'], $c['mensagem'], $email_senha['RemetenteEmail'], $email_senha['RemetenteNome'], $email_senha['DestinoEmail'], $email_senha['DestinoNone'], $email_senha['RemetenteEmail'], $replyNome = NULL, $anexo_pasta = NULL);
    // if ($enviar_envio) :
    //     echo 'Email enviado com sucesso.';
    // else :
    //     // echo 'Erro ao enviar o Email.';
    //     // var_dump($enviar_envio);
    // endif;
}
