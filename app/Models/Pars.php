<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Orchestra\Parser\Xml\Facade as XmlParser;

class Pars extends Model
{
    public function Pars1()
    {
//        Storage::disk('local')->put('https://service.ferroli.ru//storage/equipments/QdKKGApSBYtPGa0Gj0RBiAXGZyhw9DV5TAM3The3.jpeg', 'Contents');
        $url = 'https://service.ferroli.ru//storage/equipments/QdKKGApSBYtPGa0Gj0RBiAXGZyhw9DV5TAM3The3.jpeg';
        $file_extension = pathinfo($url)['extension'];
        $test = 1;
        $file_name='good/'.Str::random(30).'.'.$file_extension;

        $file = file_get_contents($url);

        $image=Storage::disk('public')->put($file_name, $file);
        dd($image);
        $xml = XmlParser::load('https://service.ferroli.ru/storage/catalogs/xml-cml.xml')->getContent();
        $characts=$xml->Классификатор->свойства->Свойство;
        $i=0;
        foreach ($characts as $charact)
        {
                $charactArray[(string)$charact->Ид[0]]=(string)$charact->Наименование[0];
        }
        $products=$xml->Каталог->Товары->Товар;
        foreach ($products as $product)
        {
            $values=$product->ЗначениеРеквизита;
            foreach ($values as $value)
            {
                if((string)$value->Наименование[0]=="Цена")
                {
                    $data['price'] = (string)$value->Значение[0];
                }
            }
            $data['tab_name'] = 'Котловое оборудование';
            $data['cat_name'] = 'FERROLI';
            $data['site_url'] = 'https://service.ferroli.ru';
            $data['name'] = (string)$product->Наименование[0];
            $data['description'] = (string)$product->Описание[0];
            $data['price'] = (string)$product->Описание[0];
            $data['1']=1;
        }
        dd($products[2]);

    }
}
