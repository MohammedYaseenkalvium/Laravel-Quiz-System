<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\QuestionTypes\QuestionTypeRegistry;
use App\QuestionTypes\Types\SingleChoiceType;
use App\QuestionTypes\Types\MultipleChoiceType;
use App\QuestionTypes\Types\BinaryType;
use App\QuestionTypes\Types\NumberType;
use App\QuestionTypes\Types\TextType;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(QuestionTypeRegistry::class, function () {
            $registry = new QuestionTypeRegistry();

            $registry->register(new SingleChoiceType());
            $registry->register(new MultipleChoiceType());
            $registry->register(new BinaryType());
            $registry->register(new NumberType());
            $registry->register(new TextType());

            return $registry;
        });
    }

    public function boot(): void
    {
        //
    }
}