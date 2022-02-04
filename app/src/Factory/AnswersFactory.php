<?php

namespace App\Factory;

use App\Entity\Answers;
use App\Repository\AnswersRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Answers>
 *
 * @method static Answers|Proxy createOne(array $attributes = [])
 * @method static Answers[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Answers|Proxy find(object|array|mixed $criteria)
 * @method static Answers|Proxy findOrCreate(array $attributes)
 * @method static Answers|Proxy first(string $sortedField = 'id')
 * @method static Answers|Proxy last(string $sortedField = 'id')
 * @method static Answers|Proxy random(array $attributes = [])
 * @method static Answers|Proxy randomOrCreate(array $attributes = [])
 * @method static Answers[]|Proxy[] all()
 * @method static Answers[]|Proxy[] findBy(array $attributes)
 * @method static Answers[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Answers[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static AnswersRepository|RepositoryProxy repository()
 * @method Answers|Proxy create(array|callable $attributes = [])
 */
final class AnswersFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            // TODO add your default values here (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories)
            'text' => self::faker()->text(),
            'createAt' => self::faker()->dateTimeBetween('-4 month', 'now'), // TODO add DATETIME ORM type manually
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Answers $answers): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Answers::class;
    }
}
