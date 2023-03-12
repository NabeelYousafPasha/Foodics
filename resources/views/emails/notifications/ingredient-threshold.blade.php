<x-mail::message>
# Ingredient Threshold Level exceeded

The Ingredient {{ $ingredient->code }} has reached / exceeded Threshold Level which was {{ round($ingredient->threshold_level) }} {{ $ingredient->threshold_level_unit }}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
