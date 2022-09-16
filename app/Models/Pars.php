<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchestra\Parser\Xml\Facade as XmlParser;

class Pars extends Model
{
    public function Pars1()
    {
        $xml = XmlParser::load('https://service.ferroli.ru/storage/catalogs/xml-cml.xml')->getContent();
        $characts=$xml->Классификатор->свойства->Свойство;
        $i=0;
        foreach ($characts as $charact)
        {
                $charactArray[(string)$charact->Ид[0]]=(string)$charact->Наименование[0];
        }
        dd($charactArray);
        $products=$xml->Каталог->Товары->Товар;
//        dd((string)$products[2]->Ид[0]);
        dd($products[2]);

    }
}
