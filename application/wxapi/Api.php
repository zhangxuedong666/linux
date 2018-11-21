namespace app\index\controller;
use Controller
use Db;
class Index extends Controller
{
    public function index()
    {
    	return $this->fetch();
    }
}