<?php

declare(strict_types=1);

namespace Libra\BxApiHelper\IblockElement;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\Loader;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Closure;
use Libra\BxApiHelper\Iblock\IblockDto;
use Libra\BxApiHelper\IblockProperties\ElementPropertyValue\ElementPropertyValueHelper;
use Libra\BxApiHelper\IblockProperties\PropertyCollection;
use Libra\BxApiHelper\IblockProperties\PropertyHelper;

class ElementCollection extends LibraElementCollection
{
    private IblockDto $iblockDto;
    private array $userGroupIds {
        set(array $value) {
            if (!in_array(2, $value)) {
                $value[] = 2;
            }
            $this->userGroupIds = $value;
        }
    }

    public function setIblockDto(IblockDto $iblockDto): self
    {
        $this->iblockDto = $iblockDto;
        return $this;
    }

    /**
     * @param Closure|null $queryFilter
     * @return $this
     * @throws ArgumentException|ObjectPropertyException|SystemException
     */
    public function withProperties(?Closure $queryFilter = null): self
    {

        $propertyCollection = PropertyHelper::getPropertyCollection($this->iblockDto->id, $queryFilter);

        $props = new ElementPropertyValueHelper($this->iblockDto);

        $elementsPropsValues = $props->getFormattedQueryResult($propertyCollection, $this->getIdList());
        /**
         * @var ElementObject $element
         */
        foreach ($this as $element) {
            $element->properties = $elementsPropsValues[$element->getId()] ?? [];
        }

        return $this;
    }

    public function withPrices(array $userGroups = [], int $quantity = 1): self
    {
        $this->userGroupIds = $userGroups;

        $priceTypes = \Bitrix\Catalog\GroupAccessTable::getList(array(
            'select' => array('CATALOG_GROUP_ID'),
            'filter' => array('@GROUP_ID' => $this->userGroupIds, '=ACCESS' => \Bitrix\Catalog\GroupAccessTable::ACCESS_BUY),
            'order' => array('CATALOG_GROUP_ID' => 'ASC')
        ))->fetchAll();
        $priceTypeList = array_column($priceTypes ?? [], 'CATALOG_GROUP_ID');

        $arPrices = \Bitrix\Catalog\PriceTable::getList([
            'select' => [
                'ID',
                'PRICE',
                'CURRENCY',
                'CATALOG_GROUP_ID',
                'PRODUCT_ID',
            ],
            'filter' => [
                '=PRODUCT_ID' => $this->getIdList(),
                '@CATALOG_GROUP_ID' => $priceTypeList,
                [
                    'LOGIC' => 'OR',
                    '<=QUANTITY_FROM' => $quantity,
                    '=QUANTITY_FROM' => null,
                ],
                [
                    'LOGIC' => 'OR',
                    '>=QUANTITY_TO' => $quantity,
                    '=QUANTITY_TO' => null,
                ]
            ],
            'order' => ['CATALOG_GROUP_ID' => 'ASC'],
        ])->fetchAll();

        $productsBasePrices = array_column($arPrices, null, 'PRODUCT_ID');

        /**
         * @var ElementObject $element
         */
        foreach ($this as $element) {
            $basePriceData = $productsBasePrices[$element->getId()] ?? [];
            unset($basePriceData['PRODUCT_ID']);

            $price = \CCatalogProduct::GetOptimalPrice(
                $element->getId(),
                $quantity,
                $this->userGroupIds,
                'N',
                [$basePriceData],
                false,
                []
            );

            $currentPrice = $price['RESULT_PRICE']['BASE_PRICE'];
            $resultCurrency = $price['RESULT_PRICE']['CURRENCY'];
            foreach ($price['DISCOUNT_LIST'] as $discount) {
                $arDiscounts = [$discount];
                $currentArPrice = [
                    'PRICE' => $currentPrice,
                    'CATALOG_GROUP_ID' => $price['PRICE']['CATALOG_GROUP_ID'],
                    'CURRENCY' => $resultCurrency,
                    'VAT_INCLUDED' => $price['RESULT_PRICE']['VAT_INCLUDED'],
                    'VAT_RATE' => $price['RESULT_PRICE']['VAT_RATE'],
                ];
                $discountResultPrice = \CCatalogDiscount::calculateDiscountList(
                    $currentArPrice,
                    $resultCurrency,
                    $arDiscounts,
                    \Bitrix\Catalog\Product\Price\Calculation::isIncludingVat()
                );
                unset($arDiscounts, $currentArPrice);

                $price['DISCOUNT_RESULT_LIST'][] =
                    [
                        'DISCOUNT_ID' => $discount['ID'],
                        //'GROUP_IDS' => $discountGroupMap[$discount['ID']],
                        ...$discountResultPrice,
                    ];
                $currentPrice = $discountResultPrice['DISCOUNT_PRICE'];
            }

            $element->price = $price;
        }

        return $this;
    }
}
