@php
    $ps = $permissions->pluck('name')->toArray();
    $groups = [];
    foreach ($ps as $key => $p) {
        $item = explode('.', $p);
        $groups[$item[0]][] = $ps[$key];
    }
@endphp

<div class="field">
    <table>
    @foreach($groups as $group_name => $group)
        <tr>
            <td><strong>{{$group_name}}</strong></td>
{{--        <div class="group">--}}
        @foreach($group as $permission)
            <td><input type="checkbox"
                   @if($role->hasPermissionTo($permission))checked @endif
                   name="permissions[]"
                   value="{{$permission}}"
                >{{$permission}}</td>
        @endforeach
{{--        </div>--}}
        </tr>
    @endforeach
    </table>
{{--        {{$role->name}}--}}


</div>
