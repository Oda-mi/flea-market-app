@component('mail::message')
# 取引完了のお知らせ

{{ $transaction->buyer->name }}さんとの取引が完了しました。

取引完了商品：{{ $transaction->item->name }}

@endcomponent
