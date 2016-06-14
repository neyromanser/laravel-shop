<?php namespace Neyromanser\LaravelShop\Repository;
use Neyromanser\LaravelShop\Model\Payment_method;
use Auth;
use DB;

class Payment_methodRepository {

    public $rules = [
        'name' => 'max:255',
        'gateway' => 'max:255',
        'description' => 'string'
    ];

    protected $keys = ['name','gateway','private_key','public_key'];

    public function all(){
        return Payment_method::orderBy('id', 'desc')->get();
    }

    public function createEmpty(){
        $item = new Payment_method;
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
        $item = new Payment_method;
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
        return Payment_method::findOrFail($id);
    }

    /**
     * Check valid user.
     *
     * @param  App\Item $dream
     * @return boolean
     */
    private function checkUser(Payment_method $item)
    {
        return true;
    }
}