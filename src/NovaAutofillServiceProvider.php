<?php

namespace ChinLeung\NovaAutofill;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class NovaAutofillServiceProvider extends ServiceProvider
{
    /**
     * Boostrap the application by adding a macro to the fields.
     *
     * @return void
     */
    public function boot() : void
    {
        Field::macro(
            'autofill',
            function (string $attribute = null, string $type= null) {
                $request = app(NovaRequest::class);

                $shouldAutofill = $request->isCreateOrAttachRequest()
                    && ($instance = $request->findParentModel());

                if ($shouldAutofill) {
                    if($type=="text") {
                        $this->withMeta([
                            'value' => $instance->{$attribute ?? $this->attribute},
                        ]);
                    } elseif ($type=="belongsTo") {
                        $this->default(
                            $instance->{$attribute ?? $this->attribute};
                        );
                    }
                }

                return $this;
            }
        );
    }
}
