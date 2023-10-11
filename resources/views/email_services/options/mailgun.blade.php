<x-forms.text-field name="settings[key]" :label="__('API Key')" :value="Arr::get($settings ?? [], 'key')" autocomplete="off" />
<x-forms.text-field name="settings[webhook_key]" :label="__('Webhook Key')" :value="Arr::get($settings ?? [], 'webhook_key')" autocomplete="off" />
<x-forms.text-field name="settings[domain]" :label="__('Domain')" :value="Arr::get($settings ?? [], 'domain')" />
<x-forms.select-field name="settings[zone]" :label="__('Zone')" :options="['EU' => 'EU', 'US' => 'US']" :value="Arr::get($settings ?? [], 'zone')" />
