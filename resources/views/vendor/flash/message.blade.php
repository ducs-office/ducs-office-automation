{{--
    Shows validation errors by default, you can opt
    not to flash validation errors by overriding
    `$flashErrror` variable in the layout as,
    @extend('layout', ['flashErrors' => true])
--}}
<x-flash :flash-errors="$flashErrors ?? true"></x-flash>
