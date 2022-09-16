<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchestra\Parser\Xml\Facade as XmlParser;

class Pars extends Model
{
    public function Pars1()
    {
        $xml = XmlParser::load('https://service.ferroli.ru/storage/catalogs/xml-cml.xml')->getContent();

        $xml=$xml->Каталог->Товары;
        dd($xml);

    }
}
