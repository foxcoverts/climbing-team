@php
    $isAside = $isAside();
@endphp

<x-filament::section
    :aside="$isAside"
    :collapsed="$isCollapsed()"
    :collapsible="$isCollapsible() && (! $isAside)"
    :compact="$isCompact()"
    :content-before="$isContentBefore()"
    :description="$getDescription()"
    :footer-actions="$getFooterActions()"
    :footer-actions-alignment="$getFooterActionsAlignment()"
    :header-actions="$getHeaderActions()"
    :heading="$getHeading()"
    :icon="$getIcon()"
    :icon-color="$getIconColor()"
    :icon-size="$getIconSize()"
    :persist-collapsed="$shouldPersistCollapsed()"
    :attributes="
        \Filament\Support\prepare_inherited_attributes($attributes)
            ->merge([
                'id' => $getId(),
            ], escape: false)
            ->merge($getExtraAttributes(), escape: false)
            ->merge($getExtraAlpineAttributes(), escape: false)
    "
>
    <div x-data="{ hasLegitimateReason: false, }">
        <p class="text-sm leading-6 text-gray-950 dark:text-white"><strong class="font-medium">Notice:</strong> You may only use these details to contact team members regarding legitimate Climbing Team matters. Any other use of these contact details, no matter how well intended, will be in breach of UK data protection laws.</p>
    
        <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3 cursor-pointer">
            <x-filament::input.checkbox required wire:loading.attr="disabled" x-model="hasLegitimateReason" />
            <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                I have a legitimate reason to view these contact details<sup class="text-danger-600 dark:text-danger-400 font-medium">*</sup>
            </span>
        </label>

        <div x-show="hasLegitimateReason" class="pt-6 mt-6 border-t border-gray-200 dark:border-white/10">
            {{ $getChildComponentContainer() }}
        </div>
    </div>
</x-filament::section>
