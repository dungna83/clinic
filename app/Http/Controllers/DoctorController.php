<?php

namespace App\Http\Controllers;

use App\Entities\Doctor;
use App\Repositories\DoctorRepositoryEloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;

class DoctorController extends MyController
{
    const USER_FULL_ACCESS = 9999;

    private $doctorRepository;

    public function __construct(
        Router $router,
        DoctorRepositoryEloquent $doctor
    ) {
        parent::__construct($router);
        $this->doctorRepository = $doctor;
    }

    public function index()
    {
        $this->header = trans('doctors.header.index');
        $data = array();
        $data['listDoctor'] = $this->doctorRepository->withTrashed()->orderBy('id', 'DESC')->all();

        return $this->view('admin.doctors.index', $data);
    }

    public function create()
    {
        $this->header = trans('doctors.header.create');
        $data = array();
        $data['fullAccess'] = self::USER_FULL_ACCESS;

        return $this->view('admin.doctors.form', $data);
    }

    public function store()
    {
        $data = $this->getAllParams();
        $validator = \Validator::make($data, [
            'name' => 'required',
            'email' => 'required|email|unique:doctors',
            'password' => 'required|min:6',
            're_password' => 'required|same:password'
        ]);
        if ($validator->fails()) {
            return $this->submitError($validator->errors());
        }
        $attributes = array(
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => \Hash::make($data['password']),
        );
        if (isset($data['role']) && $data['role'][0] == self::USER_FULL_ACCESS) {
            $attributes['system_admin'] = 1;
        } else {
            $attributes['system_admin'] = 0;
        }
        $this->doctorRepository->create($attributes);

        return redirect()->route('doctors.index')->with(['message' => trans('doctors.message.create')]);
    }

    public function edit($id)
    {
        $this->header = trans('doctors.header.edit');
        $data = array();
        $data['doctor'] = $this->doctorRepository->find($id);
        $data['fullAccess'] = self::USER_FULL_ACCESS;

        return $this->view('admin.doctors.form', $data);
    }

    public function update($id)
    {
        $data = $this->getAllParams();
        $validator = \Validator::make($data, [
            'name' => 'required',
            'email' => 'required|email|unique:doctors,email,' . $id . ',id',
            'password' => 'min:6',
            're_password' => 'same:password'
        ]);
        if ($validator->fails()) {
            return $this->submitError($validator->errors());
        }

        $attributes = array(
            'name' => $data['name'],
            'email' => $data['email'],
        );
        if (!empty($data['password'])) {
            $attributes['password'] = \Hash::make($data['password']);
        }
        $this->doctorRepository->update($attributes, $id);

        return redirect()->route('doctors.index')->with(['message' => trans('doctors.message.edit')]);
    }

    public function destroy($id)
    {
        $doctor = $this->doctorRepository->withTrashed()->find($id);

        if (!empty($doctor->deleted_at)) {
            $this->doctorRepository->restore($doctor);
            $message = trans('doctors.message.restore');
        } else {
            $this->doctorRepository->delete($id);
            $message = trans('doctors.message.delete');
        }

        return redirect()->route('doctors.index')->with(['message' => $message]);
    }
}
