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
                if((string)$value->Наименование[0] === "Цена")
                {
                    $data['price'] = (string)$value->Значение[0];
                }
            }
            $data['tab_name'] = 'Котловое оборудование';
            $data['cat_name'] = 'FERROLI';
            $data['site_url'] = 'https://service.ferroli.ru';
            $data['name'] = (string)$product->Наименование[0];
            $data['description'] = (string)$product->Описание[0];
            $url = (string)$product->Картинка[0];
            $file_extension = pathinfo($url)['extension'];
            $file_name='good/'.Str::random(30).'.'.$file_extension;
            $file = file_get_contents($url);
            Storage::disk('public')->put($file_name, $file);
            $data['image']=str_replace('http://localhost','',Storage::disk('public')->url($file_name));
            $good = Good_p::create($data);
            $good_characts=$product->ЗначенияСвойства;
            foreach ($good_characts as $good_charact)
            {
                $dataCharact['good_p_id']=$good->id;
                $dataCharact['name']=$charactArray[(string)$good_charact->Ид[0]];
                $dataCharact['value']=(string)$good_charact->Значение[0];
                Characteristic_p::create($dataCharact);

            }
        }

    }
}
