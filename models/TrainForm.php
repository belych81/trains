<?php

namespace app\models;

use Yii;
use yii\base\Model;


class TrainForm extends Model
{
    public $number;
    public $train;
    public $station;
    public $arrive;
    public $departure;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['number', 'train', 'station'], 'required'],
            [['arrive', 'departure'], 'safe'],
        ];
    }

    public function add()
    {
        if ($this->validate()) {
            if ($station = Station::findOne(['name' => $this->station])) {
                $stationId = $station->id;
            } else {
                $station = new Station();
                $station->name = $this->station;
                if ($station->save()) {
                    $stationId = $station->id;
                }
            }

            if ($train = Train::findOne(['name' => $this->train])) {
                $trainId = $train->id;
            } else {
                $train = new Train();
                $train->number = $this->number;
                $train->name = $this->train;
                
                if ($train->save()) {
                    $trainId = $train->id;
                }
            }

            if ($trainId && $stationId) {
                $items = new Items();
                $items->train_id = $trainId;
                $items->station_id = $stationId;
                $items->arrive = str_replace("-", ":", $this->arrive);
                $items->departure = str_replace("-", ":", $this->departure);
                
                if (($items->arrive || $items->departure) && $items->save()) {
                    return true;
                }
            }

            return false;
        }
        return false;
    }
}
