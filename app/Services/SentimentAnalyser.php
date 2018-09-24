<?php

namespace App\Services;

use Phpml\Transformer;
use Phpml\ModelManager;
use Phpml\Dataset\ArrayDataset;
use Phpml\Classification\Classifier;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\CrossValidation\StratifiedRandomSplit;

class SentimentAnalyser implements SentimentAnalyserInterface
{

    protected $dataset;
    protected $vectorizer;
    protected $tfIdfTransformer;
    protected $classifier;
    protected $modelManager;

    public function __construct(
        Transformer $vectorizer,
        TfIdfTransformer $tfIdfTransformer,
        Classifier $classifier
    ) {
        $this->vectorizer = $vectorizer;
        $this->tfIdfTransformer = $tfIdfTransformer;
        $this->classifier = $classifier;
    }

    public function train(ArrayDataset $dataset)
    {
        $samples = [];
        foreach ($dataset->getSamples() as $sample) {
            $samples[] = $sample[0];
        }

        $this->vectorizer->fit($samples);
        $this->vectorizer->transform($samples);

        $this->tfIdfTransformer->fit($samples);
        $this->tfIdfTransformer->transform($samples);

        $dataset = new ArrayDataset($samples, $dataset->getTargets());
        $randomSplit = new StratifiedRandomSplit($dataset, 0.1);

        $this->classifier->train($randomSplit->getTrainSamples(), $randomSplit->getTrainLabels());
        $predictedLabels = $this->classifier->predict($randomSplit->getTestSamples());
    }


    public function predict(array $samples)
    {
        $this->vectorizer->fit($samples);
        $this->vectorizer->transform($samples);

        return $this->classifier->predict($samples);
    }


}