<?php

namespace App\Modules\FiltersModule\Components;

use App\Enums\Filters;
use App\Models\Eloquent\PaymentParentMethod;

class PaymentService extends BaseComponent
{
    /**
     * @inheritDoc
     */
    public function getValue(): array
    {
        $this->filters->payments->hideValues();
        $aggregatedPaymentMethods = $this->elasticWrapper->prepareAggrData(
            $this->getData(),
            $this->paymentsFilterComponent::AGGR_PAYMENT_IDS
        );
        $this->filters->payments->showValues();

        if (!$aggregatedPaymentMethods) {
            return [];
        }

        $chosen = $this->filters->payments->getValues();
        $paymentMethods = PaymentParentMethod::getForFilters(array_keys($aggregatedPaymentMethods))
            ->map(function (PaymentParentMethod $paymentMethod) use ($aggregatedPaymentMethods, $chosen) {
                $id = $paymentMethod->id;
                $name = $paymentMethod->name;
                $title = $paymentMethod->title;

                if ($chosen->contains($id)) {
                    $isValueChosen = true;

                    $this->chosen[Filters::PARAM_PAYMENTS][$name] = [
                        'id' => $id,
                        'name' => $name,
                        'option_title' => __('filters.' . Filters::PARAM_PAYMENTS),
                        'option_value_title' => $title,
                        'comparable' => Filters::COMPARABLE_MAIN,
                    ];
                } else {
                    $isValueChosen = false;
                }

                return [
                    'option_value_id' => $id,
                    'option_value_name' => $name,
                    'option_value_title' => $title,
                    'is_chosen' => $isValueChosen,
                    'products_quantity' => $aggregatedPaymentMethods[$paymentMethod->id] ?? 0,
                    'order' => $paymentMethod->order,
                ];
            });

        return [
            Filters::PARAM_PAYMENTS => [
                'option_id' => Filters::PARAM_PAYMENTS,
                'option_name' => Filters::PARAM_PAYMENTS,
                'option_title' => __('filters.' . Filters::PARAM_PAYMENTS),
                'option_type' => Filters::OPTION_TYPE_LIST,
                'special_combobox_view' => Filters::SPECIAL_COMBOBOX_VIEW_LIST,
                'comparable' => Filters::COMPARABLE_MAIN,
                'hide_block' => false,
                'total_found' => $paymentMethods->count(),
                'option_values' => $paymentMethods,
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    public function getFilterQuery(): array
    {
        return $this->paymentsFilterComponent->getValue();
    }
}
