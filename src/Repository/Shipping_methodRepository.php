<?php namespace Neyromanser\LaravelShop\Repository;

use Neyromanser\LaravelShop\Model\Shipping_method;
use Auth;
use DB;

class Shipping_methodRepository {

    public $rules = [
        'name' => 'max:255',
    ];

    protected $keys = ['name','api_key','api'];

    public function all(){
        return Shipping_method::orderBy('id', 'desc')->get();
    }

    public function createEmpty(){
        $item = new Shipping_method;
        $item->name = 'Заголовок';
        $item->save();
        return $item;
    }

    /**
     * Store an item.
     *
     * @param  array  $inputs
     * @param  integer $user_id
     * @return boolean
     */
    public function store($inputs)
    {
        $item = new Shipping_method;
        foreach ($this->keys as $key){
            if(array_key_exists($key, $inputs))
                $item->$key = $inputs[$key];
        }
        $item->save();

        return $item;
    }

    /**
     * Update a item.
     *
     * @param  array  $inputs
     * @param  integer $id
     * @return boolean
     */
    public function update($inputs, $id)
    {
        $item = $this->getById($id);
        if ($this->checkUser($item))
        {
            foreach ($this->keys as $key){
                if(array_key_exists($key, $inputs))
                    $item->$key = $inputs[$key];
            }

            $item->save();
            return $item;
        }
        return false;
    }

    /**
     * Destroy an item.
     *
     * @param  integer $id
     * @return boolean
     */
    public function destroy($id)
    {
        $item = $this->getById($id);
        if ($this->checkUser($item))
        {
            return $item->delete();
        }
        return false;
    }

    /**
     * Get an item by id.
     *
     * @param  integer $id
     * @return boolean
     */
    public function getById($id)
    {
        return Shipping_method::findOrFail($id);
    }

    /**
     * Check valid user.
     *
     * @param  App\Item $dream
     * @return boolean
     */
    private function checkUser(Shipping_method $item)
    {
        return true;
    }
}