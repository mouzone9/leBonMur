<?php

namespace App\Factory;

use App\Entity\Advertisement;
use App\Repository\AdvertisementRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Advertisement>
 *
 * @method static Advertisement|Proxy createOne(array $attributes = [])
 * @method static Advertisement[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Advertisement|Proxy find(object|array|mixed $criteria)
 * @method static Advertisement|Proxy findOrCreate(array $attributes)
 * @method static Advertisement|Proxy first(string $sortedField = 'id')
 * @method static Advertisement|Proxy last(string $sortedField = 'id')
 * @method static Advertisement|Proxy random(array $attributes = [])
 * @method static Advertisement|Proxy randomOrCreate(array $attributes = [])
 * @method static Advertisement[]|Proxy[] all()
 * @method static Advertisement[]|Proxy[] findBy(array $attributes)
 * @method static Advertisement[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Advertisement[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static AdvertisementRepository|RepositoryProxy repository()
 * @method Advertisement|Proxy create(array|callable $attributes = [])
 */
final class AdvertisementFactory extends ModelFactory
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
            'title' => self::faker()->realText(50),
            'description' => self::faker()->text(),
            'price' => self::faker()->randomFloat(2, 1, 2000),
            'publicationDate' => self::faker()->dateTimeBetween('-6 month', 'now'), // TODO add DATETIME ORM type manually
            'pictures' => self::getImageArray(),
            'status' => self::faker()->randomElement([
                Advertisement::$PUBLIC_STATUS,
                Advertisement::$DRAFT_STATUS,
                Advertisement::$SOLD_STATUS
            ])
        ];
    }


    private function getImageArray(): array
    {
        $result = [];
        $rand = rand(1, 10);
        for ($i = 0; $i < $rand; $i++) {
            $result[] = self::faker()->imageUrl();
        }
        return $result;
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this// ->afterInstantiate(function(Advertisement $advertisement): void {})
            ;
    }

    protected static function getClass(): string
    {
        return Advertisement::class;
    }
}
