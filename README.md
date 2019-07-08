# yii2-ajax-create

Это расширение предназначено для отображния форм в модальном окне.


## Установка

Предпочтительный способ установки этого через расширения [composer](http://getcomposer.org/download/).

Вы можете установить его из консоли

``
~ $ composer require lav45/yii2-ajax-create
``

или добавить

``
      "lav45/yii2-ajax-create": "0.2.*"
``

в разделе "require" в `composer.json` файл.


## Принцип работы

На странице создается кнопка при нажатию на которую у нас будет открываться модальное окно с формой.
Если данные введены не верно тогда с сервера приходит список ошибок которые отображаются на форме в модальном окне.
После успешного сохранения данных, будет обновлен контент который находится внутри блока `AjaxCreate` 


## Использование

Для начала нам нужна кнопка при нажатию на которую у нас будет открываться модальное окно.
`data-href` - ссылка по которой открывается форма

```php
use lav45\widget\AjaxCreate;

AjaxCreate::begin();

echo Html::button('<span class="glyphicon glyphicon-plus"></span>', [
    'data-href' => Url::toRoute(['create']),
    'class' => 'btn btn-success',
]);

AjaxCreate::end();
```

Пример контроллера. Для корректного отображения формы в модальном окне её нужно отобразить с помощью метода `renderPartial()`. 

```php
class SiteController extends Controller
{
    public function actionCreate()
    {
        // ...
        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }
}
```

В представлении с формой в первую очередь следует переопределить `ActiveForm::$autoIdPrefix` на любое значение, т.к. форма будет отображаться в модальном окне на странице на которой уже присутствуют элементы с `id="w1"` что приведет к ошибкам при работе js кода.

```php
// views/site/_form.php

use yii\bootstrap\ActiveForm;

ActiveForm::$autoIdPrefix = 'a';

$form = ActiveForm::begin([
    'layout' => 'horizontal',
]);
// ...
```
