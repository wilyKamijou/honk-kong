<!-- resources/views/partials/admin-table-actions.blade.php -->
<div class="action-buttons">
    <a href="{{ $editRoute }}" class="btn btn-sm admin-btn-info">
        <i class="bi bi-pencil"></i>
    </a>
    <form action="{{ $deleteRoute }}" method="POST">
        @csrf
        @method('delete')
        <button type="submit" class="btn btn-sm admin-btn-danger">
            <i class="bi bi-trash"></i>
        </button>
    </form>
</div>