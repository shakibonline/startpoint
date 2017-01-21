<?php

namespace StartPoint\Http\Controllers\Admin;

use Illuminate\Http\Request;

use StartPoint\Http\Requests;
use StartPoint\Http\Controllers\Controller;
use StartPoint\TicketCategory;

class TicketCategoriesController extends Controller
{
<<<<<<< HEAD:app/Http/Controllers/Admin/DepartmentsController.php

    public function index()
    {
        return view('admin.departments.index');
    }

    public function create()
    {
        $departments = Department::all();

        return view('admin.departments.create', ['departments' => $departments]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'parent_id' => 'required',
        ]);
        $data = $request->all();
        Department::create($data);
        return redirect('/admin/departments');
    }

    public function edit($id)
    {
        $departments = Department::all();
        $department = Department::find($id);
        return view('admin.departments.edit', ['department' => $department, 'departments' => $departments]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'parent_id' => 'required',
        ]);
        $department = Department::find($id);
        $data = $request->all();
        $department->update($data);
        return redirect('/admin/departments');
    }

    public function destroy($id)
    {
        Department::destroy($id);
        return redirect()->back();
=======
    public function index()
    {
        return view('admin.ticket_categories.index');
>>>>>>> 821d440c0072a6b1326f50a9c05c6167ef468caa:app/Http/Controllers/Admin/TicketCategoriesController.php
    }

    public function grid(Request $request)
    {
        if ($request->ajax() && $request->exists('req')) {
            $req = json_decode($request->get('req'));
            $perPage = $req->page->perPage;
            $from = $perPage * (($req->page->currentPage) - 1);

            $query = TicketCategory::select(['id', 'name', 'order', 'published']);

            if (!is_null($req->sort)) {
                foreach ($req->sort as $key => $value) {
                    $query->orderBy($key, $value);
                }
            }
            if (!is_null($req->filter)) {
                foreach ($req->filter as $key => $value) {
                    switch ($value->operator) {
                        case 'IsEqualTo':
                            $query->where($key, '=', $value->operand1);
                            break;
                        case 'IsNotEqualTo':
                            $query->where($key, '<>', $value->operand1);
                            break;
                        case 'StartWith':
                            $query->where($key, 'LIKE', $value->operand1 . '%');
                            break;
                        case 'Contains':
                            $query->where($key, 'LIKE', '%' . $value->operand1 . '%');
                            break;
                        case 'DoesNotContains':
                            $query->where($key, 'NOT LIKE', '%' . $value->operand1 . '%');
                            break;
                        case 'EndsWith':
                            $query->where($key, 'LIKE', '%' . $value->operand1);
                            break;
                        case 'Between':
                            $query->whereBetween($key, array($value->operand1, $value->operand2));
                            break;
                    }
                }
            }
            $total = $query->count();
            $query->take($perPage)->skip($from);
            $data = $query->get();
            $totalPage = ceil($total / $perPage);

            $countDataPerPage = count($data);
            $page = array(
                "currentPage" => $req->page->currentPage,
                "lastPage" => $totalPage,
                "total" => $total,
                "from" => $from + 1,
                "count" => $countDataPerPage,
                "perPage" => $perPage,
            );
            $result = ['data' => $data, 'page' => $page];
            return json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
    }

    public function create()
    {
        return view('admin.ticket_categories.create')->with([
            'pageTitle' => 'ایجاد دسته بندی جدید'
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3',
        ]);
        $data = $request->all();
        $data['alias'] = (empty($data['alias'])) ? str_replace(" ", "-", $data['name']) : str_replace(" ", "-", $data['alias']);;
        $data['user_id'] = \Auth::id();
        $data['published'] = $request->has('published');
        TicketCategory::create($data);
        return redirect('/admin/ticket-categories');
    }

    public function edit($id)
    {
        return view('admin.ticket_categories.edit')->with('ticketCategory', TicketCategory::find($id));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|min:3',
        ]);

        $category = TicketCategory::find($id);
        $data = $request->all();
        $data['alias'] = (empty($data['alias'])) ? str_replace(" ", "-", $data['name']) : str_replace(" ", "-", $data['alias']);;
        $data['user_id'] = \Auth::id();
        $data['published'] = $request->has('published');
        $category->update($data);
        return redirect('/admin/ticket-categories');

    }

    public function destroy($id)
    {
        // TODO: check category posts
        TicketCategory::destroy($id);
        return redirect()->back();
    }
}
