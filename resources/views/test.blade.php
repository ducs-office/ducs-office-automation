@extends('layouts.master')
@section('body')
@php($selectedUsers = \App\User::find([2, 3, 7]))
<div class="page-card m-4 p-6">
    <div class="page-header">Test</div>
    {{-- <form action="" method="get" class="flex">
        <v-multi-typeahead name="field"
            class="flex-1 mr-1"
            data-source="/api/users"
            data-key="id"
            :initial-options="{{ [] }}"
            :value="{{ old('field', [2, 3, 7]) }}">
        </v-multi-typeahead>
        <button type="submit" class="btn btn-magenta">Submit</button>
    </form> --}}
    <button class="btn btn-magenta" @click="$modal.show('update', {
        name: 'my name',
        another: {
            name: 'other name',
            users: ['john', 'mary', 'tom', 'harry']
        }
    })">POP UP</button>
    <role-update-modal name="update">
        <template v-slot="{ data }">
            <div>Hello</div>
            <div v-text="data('name')"></div>
            <div v-text="data('another.name')"></div>
            <div v-for="user in data('another.users')" v-text="user"></div>
        </template>
    </role-update-modal>
</div>
@endsection
