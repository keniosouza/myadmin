<?php

/** Defino o local da classe */
namespace vendor\controller\highlighters;

/** Importação de classes */
use \vendor\model\Geral;
use \vendor\model\Highlighters;

class HighlightersQualify
{

    /** Variaveis da classe */
    private $Geral = null;
    private $Highlighters = null;

    private $string = null;
    private $primaryKeyValue = null;
    private $table = null;

    /** Método construtor */
    public function __construct()
    {

        /** Instânciamento de classes */
        $this->Geral = new Geral();
        $this->Highlighters = new Highlighters();

    }

    /** Extraio as marcações do texto */
    public function getHighlighters(string $string) : array
    {

        /** Parâmetros de entrada */
        $this->string = $string;

        /** Busco as marcações para substituição */
        preg_match_all("#\[[\w\s']+\]#i", $this->string, $palavras);

        /** Retorno a sequencia */
        return (array)$palavras[0];

    }

    public function Qualify(string $string, int $primaryKeyValue, string $table) : string
    {

        /** Parâmetros de entrada */
        $this->string = base64_decode($string);
        $this->primaryKeyValue = $primaryKeyValue;
        $this->table = $table;

        /** Percorro todas as palavras localizadas */
        foreach ($this->getHighlighters($this->string) as $keyWord => $word)
        {

            /** Busco as informações da marcação */
            $resultHighlighter = $this->Highlighters->GetByName($word);

            /** Decodifico a estrutra do texto */
            $resultHighlighter->text = (object)json_decode($resultHighlighter->text);

            /** Verifico se a marcação foi localizada */
            if (@(int)$resultHighlighter->highlighter_id > 0 && $resultHighlighter->text->table === $this->table)
            {

                /** Busco a marcação */
                $result = utf8_encode(@(string)$this->Geral->Get($resultHighlighter->text->table, $resultHighlighter->text->primary_key, $resultHighlighter->text->column, $this->primaryKeyValue));

                /** Preenchimento da marcação */
                $this->string = str_replace($word, $result, $this->string);

            }

        }

        /** Retorno da informação */
        return (string)base64_encode($this->string);

    }

}
