<?php

namespace App\Factory;

use App\Entity\Comments;
use App\Repository\CommentsRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Comments>
 *
 * @method static Comments|Proxy createOne(array $attributes = [])
 * @method static Comments[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Comments|Proxy find(object|array|mixed $criteria)
 * @method static Comments|Proxy findOrCreate(array $attributes)
 * @method static Comments|Proxy first(string $sortedField = 'id')
 * @method static Comments|Proxy last(string $sortedField = 'id')
 * @method static Comments|Proxy random(array $attributes = [])
 * @method static Comments|Proxy randomOrCreate(array $attributes = [])
 * @method static Comments[]|Proxy[] all()
 * @method static Comments[]|Proxy[] findBy(array $attributes)
 * @method static Comments[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Comments[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static CommentsRepository|RepositoryProxy repository()
 * @method Comments|Proxy create(array|callable $attributes = [])
 */
final class CommentsFactory extends ModelFactory
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
            'createdAt' => self::faker()->dateTimeBetween('-4 month', 'now'), // TODO add DATETIME ORM type manually
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Comments $comments): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Comments::class;
    }
}
