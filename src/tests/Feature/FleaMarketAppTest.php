<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use App\Models\Category;
use App\Models\Purchase;

use Database\Seeders\ConditionTableSeeder;
use Database\Seeders\CategoryTableSeeder;

use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\URL;


class FleaMarketAppTest extends TestCase
{
    use RefreshDatabase;

    // ========================================
    // １.会員登録機能テスト
    // ========================================
    /** @test */
    public function 名前が入力されていない場合、バリデーションメッセージが表示される()
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'test@email.com',
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ]);

        $errors = session('errors')->get('name');
        $this->assertEquals('お名前を入力してください', $errors[0]);
    }

    /** @test */
    public function メールアドレスが入力されていない場合、バリデーションメッセージが表示される()
    {
        $response = $this->post('/register', [
            'name' => 'テスト太郎',
            'email' => '',
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ]);

        $errors = session('errors')->get('email');
        $this->assertEquals('メールアドレスを入力してください', $errors[0]);
    }

    /** @test */
    public function パスワードが入力されていない場合、バリデーションメッセージが表示される()
    {
        $response = $this->post('/register', [
            'name' => 'テスト太郎',
            'email' => 'test@email.com',
            'password' => '',
            'password_confirmation' => ''
        ]);

        $errors = session('errors')->get('password');
        $this->assertEquals('パスワードを入力してください', $errors[0]);
    }

    /** @test */
    public function パスワードが7文字以下の場合、バリデーションメッセージが表示される()
    {
        $response = $this->post('/register', [
            'name' => 'テスト太郎',
            'email' => 'test@email.com',
            'password' => '1234567',
            'password_confirmation' => '1234567'
        ]);

        $errors = session('errors')->get('password');
        $this->assertEquals('パスワードは８文字以上で入力してください', $errors[0]);
    }

    /** @test */
    public function パスワードが確認パスワードと一致しない場合、バリデーションメッセージが表示される()
    {
        $response = $this->post('/register', [
            'name' => 'テスト太郎',
            'email' => 'test@email.com',
            'password' => '12345678',
            'password_confirmation' => '01234567'
        ]);

        $errors = session('errors')->get('password');
        $this->assertEquals('パスワードと一致しません', $errors[0]);
    }

    /** @test */
    public function 全ての項目が入力されている場合、会員情報が登録され、プロフィール設定画面に遷移される()
    {
        $response = $this->post('/register', [
            'name' => 'テスト太郎',
            'email' => 'test@email.com',
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ]);

        $response->assertRedirect(route('verification.notice'));

        $this->assertDatabaseHas('users', [
            'email' => 'test@email.com',
        ]);
    }

    // ========================================
    // ２.ログイン機能テスト
    // ========================================
     /** @test */
    public function メールアドレスが入力されてない場合、バリデーションメッセージが表示される()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => '12345678',
        ]);

        $errors = session('errors')->get('email');
        $this->assertEquals('メールアドレスを入力してください', $errors[0]);
    }

     /** @test */
    public function パスワードが入力されてない場合、バリデーションメッセージが表示される()
    {
        $response = $this->post('/login', [
            'email' => 'test@email.com',
            'password' => '',
        ]);

        $errors = session('errors')->get('password');
        $this->assertEquals('パスワードを入力してください', $errors[0]);
    }

     /** @test */
    public function 入力情報が間違っている場合、バリデーションメッセージが表示される()
    {
        $response = $this->post('/login', [
            'email' => 'wrong@email.com',
            'password' => 'wrongpassword',
        ]);

        $errors = session('errors')->get('email');
        $this->assertEquals('ログイン情報が登録されていません', $errors[0]);
    }

     /** @test */
    public function 正しい情報が入力された場合、ログイン処理が実行される()
    {
        $user = User::factory()->create([
            'email' => 'test@email.com',
            'password' => Hash::make('12345678'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@email.com',
            'password' => '12345678',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/');
    }

    // ========================================
    //  ３.ログアウト機能テスト
    // ========================================
     /** @test */
    public function ログアウトができる()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/logout');

        $this->assertGuest();

        $response->assertRedirect('/login');
    }

    // ========================================
    // ４.商品一覧取得テスト
    // ========================================
   /** @test */
    public function 全商品を取得できる()
    {
        $this->seed(ConditionTableSeeder::class);

        $items = Item::factory()->count(5)->create();

        $response = $this->get(route('items.index'));

        foreach ($items as $item) {
            $response->assertSee($item->name);
        }
    }

    /** @test */
    public function 購入済み商品は「Sold」と表示される()
    {
        $this->seed(ConditionTableSeeder::class);
        $condition = Condition::first();

        $user = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'is_sold' => true,
        ]);

        $response = $this->get(route('items.index'));

        $response->assertSee('Sold');
    }

    /** @test */
    public function 自分が出品した商品は表示されない()
    {
        $this->seed(ConditionTableSeeder::class);
        $condition = Condition::first();

        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $myItem = Item::factory()->create([
            'name' => 'my-item',
            'user_id' => $user->id,
            'condition_id' => $condition->id,
        ]);
        $otherItem = Item::factory()->create([
            'name' => 'other-item',
            'user_id' => $otherUser->id,
            'condition_id' => $condition->id,
        ]);

        $this->actingAs($user);

        $response = $this->get(route('items.index'));

        $response->assertSeeText('other-item');
        $response->assertDontSeeText('my-item');
    }

    // ========================================
    // 5.マイリスト一覧取得テスト
    // ========================================
    /** @test */
    public function いいねした商品だけが表示される()
    {
        $this->seed(ConditionTableSeeder::class);
        $condition = Condition::first();

        $user = User::factory()->create();
        $this->actingAs($user);

        $seller = User::factory()->create();

        $likedItem = Item::factory()->create([
            'name' => 'liked-item',
            'user_id' => $seller->id,
            'condition_id' => $condition->id,
        ]);
        $notLikedItem = Item::factory()->create([
            'name' => 'not-liked-item',
            'user_id' => $seller->id,
            'condition_id' => $condition->id,
        ]);

        $user->favoriteItems()->attach($likedItem->id);

        $response = $this->get(route('items.index', ['page' => 'myList']));

        $response->assertSee('liked-item');
        $response->assertDontSee('not-liked-item');
    }

    /** @test */
    public function マイリストで購入済み商品は「Sold」と表示される()
    {
        $this->seed(ConditionTableSeeder::class);
        $condition = Condition::first();

        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create([
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'is_sold' => true,
        ]);

        $user->favoriteItems()->attach($item->id);

        $response = $this->get(route('items.index', ['page' => 'myList']));

        $response->assertSee('Sold');
    }

    /** @test */
    public function 未認証の場合はマイリストに何も表示されない()
    {
        $this->seed(ConditionTableSeeder::class);

        $likedItem = Item::factory()->create([
            'name' => 'テスト商品',
        ]);

        $response = $this->get(route('items.index', ['page' => 'myList']));

        $response->assertDontSee('テスト商品');
    }

    // ========================================
    // ６.商品検索機能テスト
    // ========================================
    /** @test */
    public function 商品名で部分一致検索ができる()
    {
        $this->seed(ConditionTableSeeder::class);

        $item1 = Item::factory()->create(['name' => '腕時計']);
        $item2 = Item::factory()->create(['name' => 'HDD']);
        $item3 = Item::factory()->create(['name' => '玉ねぎ３束']);

        $response = $this->get(route('items.index', [
            'keyword' => '腕時計',
        ]));

        $response->assertSee('腕時計');
        $response->assertDontSee('HDD');
        $response->assertDontSee('玉ねぎ３束');
    }

    /** @test */
    public function 検索状態がマイリストでも保持されている()
    {
        $this->seed(ConditionTableSeeder::class);

        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create(['name' => '腕時計']);
        $user->favoriteItems()->attach($item->id);

        $response = $this->get(route('items.index', [
            'page' => 'myList',
            'keyword' => '腕時計',
        ]));

        $response->assertSee('腕時計');
    }

    // ========================================
    // ７.商品詳細情報取得テスト
    // ========================================
    /** @test */
    public function 商品詳細ページに必要な情報が表示される()
    {
        $this->seed(ConditionTableSeeder::class);
        $condition = Condition::first();
        $this->seed(CategoryTableSeeder::class);

        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create([
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'price' => 1000,
            'description' => 'テスト商品の説明',
            'img_url' => 'dummy-item.png',
            'condition_id' => $condition->id,
            'user_id' => $user->id,
            'is_sold' => true,
        ]);

        $user->favoriteItems()->attach($item->id);
        $commentUser = User::factory()->create(['name' => 'テスト太郎']);
        $item->comments()->create([
            'user_id' => $commentUser->id,
            'comment' => 'テストコメント'
        ]);

        $response = $this->get(route('items.show', ['item_id' => $item->id]));

        $response->assertSee($item->name);
        $response->assertSee($item->brand);
        $response->assertSee(number_format($item->price));
        $response->assertSee($item->description);
        $response->assertSee($item->img_url);
        $response->assertSee((string)$item->categories()->first()->name);

        $item->categories->each(function ($category) use ($response) {
        $response->assertSee($category->name);
        });

        $response->assertSee('<span class="item-actions__favorite-count">1</span>', false);
        $response->assertSee('<span class="item-actions__comment-count">1</span>', false);
        $response->assertSee($commentUser->name);
        $response->assertSee($item->comments->first()->comment);
    }

    /** @test */
    public function 複数選択されたカテゴリが表示されているか()
    {
        $this->seed(ConditionTableSeeder::class);
        $condition = Condition::first();
        $this->seed(CategoryTableSeeder::class);

        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create([
            'user_id' => $user->id,
            'condition_id' => $condition->id,
        ]);

        $response = $this->get(route('items.show', ['item_id' => $item->id]));

        $item->categories->each(function ($category) use ($response) {
            $response->assertSee($category->name);
        });
    }

    // ========================================
    // ８.いいね機能テスト
    // ========================================
    /** @test */
    public function いいねアイコンを押下することによって、いいねした商品として登録することができる()
    {
        $this->seed(ConditionTableSeeder::class);
        $condition = Condition::first();

        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create([
            'user_id' => $user->id,
            'condition_id' => $condition->id,
        ]);

        $likedItem = Item::factory()->create();

        $this->assertCount(0, $user->favoriteItems);

        $response = $this->post(route('items.toggleFavorite', ['item' => $item->id]));

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->assertCount(1, $user->fresh()->favoriteItems);

        $response->assertStatus(302);
    }

    /** @test */
    public function 追加済みのアイコンは色が変化する()
    {
        $this->seed(ConditionTableSeeder::class);
        $condition = Condition::first();

        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create([
            'user_id' => $user->id,
            'condition_id' => $condition->id,
        ]);

        $user->favoriteItems()->attach($item->id);

        $response = $this->get(route('items.show', ['item_id' => $item->id]));

        $response->assertSee('star-icon-active.png');
    }

    /** @test */
    public function 再度いいねアイコンを押下することによって、いいねを解除することができる()
    {
        $this->seed(ConditionTableSeeder::class);
        $condition = Condition::first();

        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create([
            'user_id' => $user->id,
            'condition_id' => $condition->id,
        ]);

        $user->favoriteItems()->attach($item->id);
        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->post(route('items.toggleFavorite', ['item' => $item->id]));

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response->assertStatus(302);
    }

    // ========================================
    // ９.コメント送信機能テスト
    // ========================================
    /** @test */
    public function ログイン済みのユーザーはコメントを送信できる()
    {
        $this->seed(ConditionTableSeeder::class);
        $condition = Condition::first();

        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create([
            'user_id' => $user->id,
            'condition_id' => $condition->id,
        ]);

        $commentData = ['comment' => 'テストコメント'];

        $response = $this->post(route('comment.store', ['item_id' => $item->id]), $commentData);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => 'テストコメント',
        ]);

        $response->assertStatus(302);
    }

    /** @test */
    public function ログイン前のユーザーはコメントを送信できない()
    {
        $this->seed(ConditionTableSeeder::class);
        $condition = Condition::first();

        $item = Item::factory()->create([
            'condition_id' => $condition->id,
        ]);

        $commentData = ['comment' => 'テストコメント'];

        $response = $this->post(route('comment.store', ['item_id' => $item->id]), $commentData);

        $this->assertDatabaseMissing('comments', [
            'item_id' => $item->id,
            'comment' => 'テストコメント',
        ]);

        $response->assertStatus(302);
    }

    /** @test */
    public function コメントが入力されていない場合、バリデーションメッセージが表示される()
    {
        $this->seed(ConditionTableSeeder::class);
        $condition = Condition::first();

        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create([
            'user_id' => $user->id,
            'condition_id' => $condition->id,
        ]);

        $commentData = ['comment' => ''];

        $response = $this->post(route('comment.store', ['item_id' => $item->id]), $commentData);

        $errors = session('errors')->get('comment');
        $this->assertEquals('コメントを入力してください', $errors[0]);
    }

    /** @test */
    public function コメントが255字以上の場合、バリデーションメッセージが表示される()
    {
        $this->seed(ConditionTableSeeder::class);
        $condition = Condition::first();

        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create([
            'user_id' => $user->id,
            'condition_id' => $condition->id,
        ]);

        $comment = str_repeat('テストコメント', 256);
        $commentData = ['comment' => $comment];

        $response = $this->post(route('comment.store', ['item_id' => $item->id]), $commentData);

        $errors = session('errors')->get('comment');
        $this->assertEquals('コメントは255文字以内で入力してください', $errors[0]);
    }

    // ========================================
    // 10.商品購入機能テスト
    // ========================================
    /** @test */
    public function 「購入する」ボタンを押下すると購入が完了する()
    {
        $this->seed(ConditionTableSeeder::class);
        $condition = Condition::first();

        $buyer = User::factory()->create();
        $this->actingAs($buyer);

        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'condition_id' => $condition->id,
            'is_sold' => false,
        ]);

        $purchase = Purchase::factory()->create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
        ]);

        $item->update(['is_sold' => true]);

        $this->assertDatabaseHas('purchases', [
            'user_id' => $buyer->id,
            'item_id' => $item->id,
        ]);

        $this->assertEquals(1, $item->fresh()->is_sold);
    }

    /** @test */
    public function 購入した商品は商品一覧画面にて「sold」と表示される()
    {
        $this->seed(ConditionTableSeeder::class);
        $condition = Condition::first();

        $buyer = User::factory()->create();
        $this->actingAs($buyer);

        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'condition_id' => $condition->id,
            'is_sold' => false,
        ]);

        $purchase = Purchase::factory()->create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
        ]);

        $item->update(['is_sold' => true]);

        $response = $this->get(route('items.index'));
        $response->assertStatus(200);

        $response->assertSee('Sold');
    }

    /** @test */
    public function 「プロフィールの購入した商品一覧」に追加されている()
    {
        $this->seed(ConditionTableSeeder::class);
        $condition = Condition::first();

        $buyer = User::factory()->create();
        $this->actingAs($buyer);

        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'condition_id' => $condition->id,
            'is_sold' => true,
            'name' => 'テスト商品',
        ]);

        $purchase = Purchase::factory()->create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get(route('mypage.index', ['page' => 'buy']));

        $response->assertSee($item->name);
    }



    // ========================================
    // 12.配送先変更機能テスト
    // ========================================
    /** @test */
    public function 送付先住所変更画面にて登録した住所が商品購入画面に反映されている()
    {
        $this->seed(ConditionTableSeeder::class);
        $condition = Condition::first();

        $buyer = User::factory()->create();
        $this->actingAs($buyer);

        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'condition_id' => $condition->id,
        ]);

        $updatedAddress = [
            'postal_code' => '123-4567',
            'address' => 'テスト県テスト町1-2-3',
            'building' => 'テストビル101',
        ];

        $this->put(route('purchase.updateAddress', ['item_id' => $item->id]), $updatedAddress);

        $response = $this->get(route('purchase.index', ['item_id' => $item->id]));
        $response->assertStatus(200);

        $response->assertSee($updatedAddress['postal_code']);
        $response->assertSee($updatedAddress['address']);
        $response->assertSee($updatedAddress['building']);
    }

    /** @test */
    public function 購入した商品に送付先住所が紐づいて登録される()
    {
        $this->seed(ConditionTableSeeder::class);
        $condition = Condition::first();

        $buyer = User::factory()->create();
        $this->actingAs($buyer);

        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'condition_id' => $condition->id,
        ]);

        $addressData = [
            'postal_code' => '123-4567',
            'address' => 'テスト県テスト町1-2-3',
            'building' => 'テストビル101',
        ];

        $this->put(route('purchase.updateAddress', ['item_id' => $item->id]), $addressData);

        $purchase = Purchase::factory()->create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'postal_code' => $addressData['postal_code'],
            'address' => $addressData['address'],
            'building' => $addressData['building'],
        ]);

        $this->assertDatabaseHas('purchases', [
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'postal_code' => $addressData['postal_code'],
            'address' => $addressData['address'],
            'building' => $addressData['building'],
        ]);
    }

    // ========================================
    // 13.ユーザー情報取得テスト
    // ========================================
    /** @test */
    public function 必要な情報が取得できる()
    {
        $this->seed(ConditionTableSeeder::class);
        $condition = Condition::first();

        $user = User::factory()->create([
            'name' => 'テスト太郎',
            'profile_image' => 'dummy-profile.png',
        ]);
        $this->actingAs($user);

        $sellItem = Item::factory()->create([
            'user_id' => $user->id,
            'name' => '出品商品A',
            'condition_id' => $condition->id,
        ]);

        $seller = User::factory()->create();

        $buyItem = Item::factory()->create([
            'user_id' => $seller->id,
            'name' => '購入商品B',
            'condition_id' => $condition->id,
        ]);

        Purchase::factory()->create([
            'user_id' => $user->id,
            'item_id' => $buyItem->id,
        ]);

        $responseSell = $this->get(route('mypage.index', ['page' => 'sell']));
        $responseSell->assertSee($user->name);
        $responseSell->assertSee($user->profile_image);
        $responseSell->assertSee($sellItem->name);
        $responseSell->assertDontSee($buyItem->name);

        $responseBuy = $this->get(route('mypage.index', ['page' => 'buy']));
        $responseBuy->assertSee($user->name);
        $responseBuy->assertSee($user->profile_image);
        $responseBuy->assertSee($buyItem->name);
        $responseBuy->assertDontSee($sellItem->name);
    }

    // ========================================
    // 14.ユーザー情報変更テスト
    // ========================================
    /** @test */
    public function 変更項目が初期値として過去設定されていること()
    {
        $user = User::factory()->create([
            'name' => 'テスト太郎',
            'profile_image' => 'dummy-profile.png',
            'postal_code' => '123-4567',
            'address' => 'テスト県テスト町1-2-3',
            'building' => 'テストビル101',
        ]);

        $this->actingAs($user);

        $response = $this->get(route('mypage.profile'));

        $response->assertSee($user['name']);
        $response->assertSee($user['profile_image']);
        $response->assertSee($user['postal_code']);
        $response->assertSee($user['address']);
        $response->assertSee($user['building']);
    }

    // ========================================
    // 15.出品商品情報登録テスト
    // ========================================
    /** @test */
    public function 商品出品画面で必要な情報が保存できること()
    {
        $this->seed(ConditionTableSeeder::class);
        $condition = Condition::first();
        $this->seed(CategoryTableSeeder::class);
        $categories = Category::take(2)->pluck('id')->toArray();

        $user = User::factory()->create();
        $this->actingAs($user);

        $itemData = [
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'description' => 'テスト商品の説明',
            'price' => 1000,
            'condition_id' => $condition->id,
            'categories' => $categories,
            'img_url' => 'dummy-item.png',
        ];

        $item = Item::create(array_merge($itemData, ['user_id' => $user->id]));

        foreach ($categories as $categoryId) {
            $item->categories()->attach($categoryId);
        }

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'name' => $itemData['name'],
            'brand' => $itemData['brand'],
            'description' => $itemData['description'],
            'price' => $itemData['price'],
            'condition_id' => $itemData['condition_id'],
            'img_url' => $itemData['img_url'],
        ]);

        foreach ($categories as $categoryId) {
            $this->assertDatabaseHas('category_item', [
                'item_id' => $item->id,
                'category_id' => $categoryId,
            ]);
        };
    }

    // ========================================
    // 16.メール認証機能テスト
    // ========================================
    /** @test */
    public function 会員登録後、認証メールが送信される()
    {
        Notification::fake();

        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $user->sendEmailVerificationNotification();

        Notification::assertSentTo($user,VerifyEmail::class);
    }

    /** @test */
    public function メール認証誘導画面で「認証はこちらから」ボタンを押下するとメール認証サイトに遷移する()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $this->actingAs($user);

        $response = $this->get(route('verification.notice'));
        $response->assertStatus(200);
        $response->assertViewIs('auth.verify-email');

        $response = $this->post(route('verification.send'));
        $response->assertRedirect();
    }

    /** @test */
    public function メール認証サイトのメール認証を完了すると、プロフィール設定画面に遷移する()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $this->actingAs($user);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->get($verificationUrl);

        $this->assertNotNull($user->fresh()->email_verified_at);

        $response->assertRedirect(route('mypage.profile'));
    }
}

