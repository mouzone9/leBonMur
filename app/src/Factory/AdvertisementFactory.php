<?php

namespace App\Factory;

use App\Entity\Advertisement;
use App\Repository\AdvertisementRepository;
use Symfony\Component\String\Slugger\SluggerInterface;
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
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
        $this->slugger = $slugger;
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
            //to get unique images
            $random = rand(0, 100000);
            $result[] = "https://picsum.photos/200/300?random=$random";
        }
        return $result;
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this->afterInstantiate(function (Advertisement $advertisement ): void {
            $advertisement->setSlug($this->slugger->slug($advertisement->getTitle()));
        });
    }

    protected static function getClass(): string
    {
        return Advertisement::class;
    }
}
