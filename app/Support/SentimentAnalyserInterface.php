<?php

namespace App\Support;

use Phpml\Dataset\ArrayDataset;

interface SentimentAnalyserInterface 
{

    public function train(ArrayDataset $dataset);
    public function predict(array $samples);

}