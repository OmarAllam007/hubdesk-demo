<?php

namespace App\Http\Requests;

use App\Category;
use App\CustomField;
use App\Item;
use App\Subcategory;

class TicketRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $this->customRules();

        $rules = collect([
            'subject' => 'required',
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'subcategory',
//            'item_id' => 'item',
            'attachments.*' => 'max:10240|mimes:jpg,png,pdf,gif,docx,xlsx,pptx,doc,xls,ppt,zip,rar'
        ]);

        $fields = collect($this->get('cf', []));
        CustomField::whereIn('id', $fields->keys())->where('required', true)->get()->reduce(function($rules, $customField) {
            return $rules->put('cf.' . $customField->id, 'required');
        }, $rules);

        return $rules->toArray();
    }

    public function messages()
    {
        $fields = collect($this->get('cf', []));
        $messages = CustomField::whereIn('id', $fields->keys())->where('required', true)->get()->reduce(function($rules, $customField) {
            return $rules->put('cf.' . $customField->id. '.required', 'This field is required');
        }, collect());

        return $messages->toArray();
    }

    protected function customRules()
    {
        \Validator::extend('subcategory', function () {
            $subcategory_id = $this->get('subcategory_id');
            if ($subcategory_id) {
                return Subcategory::where('id', $subcategory_id)->exists();
            }

            return (Category::find($this->get('category_id'))->subcategories()->count() == 0);
        });

        \Validator::extend('item', function () {
            $item_id = $this->get('item_id');
            if ($item_id) {
                return Item::where('id', $item_id)->exists();
            }

            if (!$this->get('subcategory_id')) {
                return true;
            }

            return (Subcategory::find($this->get('subcategory_id'))->items()->count() == 0);
        });
    }
}
