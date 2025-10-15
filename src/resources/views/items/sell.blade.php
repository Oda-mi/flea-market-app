@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/sell.css') }}">
@endsection

@section('content')

<div class="sell">
    <div class="sell__content">
        <h2 class="sell__heading">商品の出品</h2>

        <form action="{{ route('items.store') }}" method="post" enctype="multipart/form-data" class="sell__form">
        @csrf

        <div class="sell__field sell__field--upload">
            <label for="product_image" class="sell__subfield-label">商品画像</label>
            <div class="sell__upload">
                <img src="" id="productPreview" class="sell__image-preview">
                <input type="file" name="image" id="product_image" class="sell__image-input">
                <label for="product_image" class="sell__image-btn">画像を選択する</label>
            </div>
            <div class="sell__field-error">
                @error('image')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="sell__field sell__field--details">
            <label class="sell__field-label">商品の詳細</label>

            <div class="sell__subfield">
                <label for="categories" class="sell__subfield-label">カテゴリー</label>
                <div class="sell__category-options">
                    @foreach ($categories as $category)
                    <label class="sell__category-checkbox">
                        <input type="checkbox" name="categories[]" value="{{ $category->id }}">
                        <span>{{ $category->name }}</span>
                    </label>
                    @endforeach
                </div>
                <div class="sell__field-error">
                    @error('categories')
                    {{ $message }}
                    @enderror
                </div>
            </div>

            <div class="sell__subfield">
                <label for="condition" class="sell__subfield-label">商品の状態</label>
                <select name="condition_id" id="condition" class="sell__select-condition">
                    <option value="" disabled {{ old('condition_id') ? '' : 'selected' }}>選択してください</option>
                    @foreach($conditions as $condition)
                    <option value="{{ $condition->id }}" {{ old('condition_id') == $condition->id ? 'selected' : '' }}>
                        {{ $condition->name }}
                    </option>
                    @endforeach
                </select>
                <div class="sell__field-error">
                    @error('condition_id')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div class="sell__field sell__field--info">
            <label class="sell__field-label">商品名と説明</label>
            <div class="sell__subfield">
                <label for="name" class="sell__subfield-label">商品名</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}">
                <div class="sell__field-error">
                    @error('name')
                    {{ $message }}
                    @enderror
                </div>
            </div>

            <div class="sell__subfield sell__subfield--brand">
                <label for="brand" class="sell__subfield-label">ブランド名</label>
                <input type="text" name="brand" id="brand" value="{{ old('brand') }}" >
            </div>

            <div class="sell__subfield">
                <label for="description" class="sell__subfield-label">商品の説明</label>
                <textarea name="description" id="description">{{ old('description') }}</textarea>
                <div class="sell__field-error">
                    @error('description')
                    {{ $message }}
                    @enderror
                </div>
            </div>

            <div class="sell__subfield">
                <label for="price" class="sell__subfield-label">販売価格</label>
                <div class="sell__price-input">
                    <input type="number" name="price" id="price" class="no-spin" value="{{ old('price') }}">
                </div>
                <div class="sell__field-error">
                    @error('price')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div class="sell__field sell__field--button">
            <button class="sell__button-submit" type="submit">出品する</button>
        </div>
    </form>

    {{--商品画像選択した瞬間に画面上のプレビュー画像を変える処理--}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('product_image');
    const preview = document.getElementById('productPreview');

    input.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if(file) {
            const reader = new FileReader();
            reader.onload = function(loadEvent) {
                preview.src = loadEvent.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });
    });
    </script>
    </div>
</div>
@endsection

