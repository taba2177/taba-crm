<form action="{{ route('works.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
    <label for="name">Name:</label>
    <input type="text" class="form-control" id="name" name="name" required>
</div>

<div class="form-group">
    <label for="category">Category:</label>
    <input type="text" class="form-control" id="category" name="category" required>
</div>

<div class="form-group">
    <label for="title">Title:</label>
    <input type="text" class="form-control" id="title" name="title" required>
</div>

<div class="form-group">
    <label for="link">Link:</label>
    <input type="url" class="form-control" id="link" name="link" required>
</div>

<div class="form-group">
    <label for="tag">Tag:</label>
    <input type="text" class="form-control" id="tag" name="tag">
</div>

<div class="form-group">
    <label for="image">Image:</label>
    <input type="file" class="form-control" id="image" name="image">
</div>

<div class="form-group">
    <label for="status">Status:</label>
    <select class="form-control" id="status" name="status">
        <option value="1">Active</option>
        <option value="0">Inactive</option>
    </select>
</div>

<button type="submit" class="btn btn-primary">Submit</button>
</form>
