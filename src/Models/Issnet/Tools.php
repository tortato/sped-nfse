<?php

namespace NFePHP\NFSe\Models\Issnet;

/**
 * Classe para a comunicação com os webservices da
 * conforme o modelo ISSNET
 *
 * @category  NFePHP
 * @package   NFePHP\NFSe\Models\Issnet\Tools
 * @copyright NFePHP Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse for the canonical source repository
 */

use NFePHP\NFSe\Models\Issnet\Rps;
use NFePHP\NFSe\Models\Issnet\Factories;
use NFePHP\NFSe\Common\Tools as ToolsBase;

class Tools extends ToolsBase
{
    public function cancelarNfse($numero, $codigoCancelamento)
    {
        $this->method = 'CancelarNfseEnvio';
        $fact = new Factories\CancelarNfse($this->certificate);
        $fact->setSignAlgorithm($this->algorithm);
        $message = $fact->render(
            $this->config->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            $this->remetenteIM,
            $this->config->cmun,
            $numero,
            $codigoCancelamento
        );
        return $this->sendRequest('', $message);
    }
    
    public function consultaNFSePorRPS()
    {
    }
    
    public function consultarUrlVisualizacaoNfse()
    {
    }
    
    public function consultarUrlVisualizacaoNfseSerie()
    {
    }
    
    
    public function recepcionarLoteRps()
    {
    }

    public function consultarNFSeEnvio(
        $numeroNFSe = '',
        $dtInicio = '',
        $dtFim = '',
        $tomador = [],
        $intermediario = []
    ) {
        $this->method = 'ConsultarNFseEnvio';
        $fact = new Factories\ConsultarNFseEnvio($this->certificate);
        $fact->setSignAlgorithm($this->algorithm);
        $message = $fact->render(
            $this->config->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            $this->remetenteIM,
            $numeroNFSe,
            $dtInicio,
            $dtFim,
            $tomador,
            $intermediario
        );
        return $this->sendRequest('', $message);
    }
    
    public function consultarNFSePorRPS()
    {
    }
    
    public function consultarLoteRps($protocolo)
    {
        $this->method = 'ConsultarLoteRpsEnvio';
        $fact = new Factories\ConsultarLoteRps($this->certificate);
        $fact->setSignAlgorithm($this->algorithm);
        $message = $fact->render(
            $this->config->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            $this->remetenteIM,
            $protocolo
        );
        return $this->sendRequest('', $message);
    }
    public function consultarSituacaoLoteRps($protocolo)
    {
        $this->method = 'ConsultarSituacaoLoteRpsEnvio';
        $fact = new Factories\ConsultarSituacaoLoteRps($this->certificate);
        $fact->setSignAlgorithm($this->algorithm);
        $message = $fact->render(
            $this->config->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            $this->remetenteIM,
            $protocolo
        );
        return $this->sendRequest('', $message);
    }
    
    protected function consultaLote($protocolo, $servico)
    {
        $this->method = $servico;
    }


    protected function sendRequest($url, $message)
    {
        //no caso do ISSNET o URL é unico para todas as ações
        $url = $this->url[$this->config->tpAmb];
        if (!is_object($this->soap)) {
            $this->soap = new \NFePHP\NFSe\Common\SoapCurl($this->certificate);
        }
        $messageText = $message;
        if ($this->withcdata) {
            $messageText = $this->stringTransform("<?xml version=\"1.0\" encoding=\"UTF-8\"?>".$message);
        }
        $request = "<". $this->method . " xmlns=\"".$this->xmlns."\">"
            . "<xml>$messageText</xml>"
            . "</". $this->method . ">";
        
        $params = [
            'xml' => $message
        ];
        
        //retorna o XML durante a fase desenvolvimento dos xml
        //depois retirar esse retorno na fase de testes com o
        //webservice, usando primeiro o SOAPUI e depois realizando
        //os testes com o soap pelo PHP
        return $messageText;
        /*
        $action = "\"". $this->xmlns ."/". $this->method ."\"";
        return $this->soap->send(
            $url,
            $this->method,
            $action,
            $this->soapversion,
            $params,
            $this->namespaces[$this->soapversion],
            $request
        );
         */
    }
}
