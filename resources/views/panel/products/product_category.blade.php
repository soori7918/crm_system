@foreach ($categories as $category)
    <option {{ in_array($category->id,$selected_categories ?? []) ? 'selected' : '' }} value={{ $category->id }}> {{str_repeat('—',$category->level)}} {{ $category->name }}</option>
@endforeach