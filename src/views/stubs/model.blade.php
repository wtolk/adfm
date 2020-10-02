{!! $php_tags !!}
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Page extends Model
{
    use AsSource;
    use HasFactory;
    use SoftDeletes;
    use Filterable;

    protected $allowedFilters = [
        'title',
    ];

    protected $allowedSorts = [
        'title',
        'created_at',
        'updated_at'
    ];
}
