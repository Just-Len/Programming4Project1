<?php

namespace App\Http\Controllers;

use App\Models\Administrator;
use App\Models\Customer;
use App\Models\Lessor;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\JwtAuth;
use App\Models\UserRole;
use App\Utils\JsonResponses;

class UserController
{
    public function index()
    {
        return JsonResponses::ok(
            'Todos los registros de usuarios',
            User::with('userRole')->get()
        );
    }

    public function indexUserRole()
    {
        return JsonResponses::ok(
            'Todos los registros de roles de usuario',
            UserRole::all()
        );
    }

    public function store(Request $request)
    {
        $data_input = $request->input('data', null);

        if ($data_input) {
            $data = json_decode($data_input, true);
            $data = array_map('trim', $data);
            $rules = [
                'name' => 'required|unique:user|max:50',
                'password' => 'required|string|max:64',
                'role_id' => 'numeric|exists:user_role',
                'email_address' => 'required|email|unique:user|max:150'
            ];
            $isValid = validator($data, $rules);
            if (!$isValid->fails()) {
                $user = new User();
                $user->name = $data['name'];
                $user->password = hash('SHA256', $data['password']);
                $user->role_id = $data['role_id'];
                $user->email_address = $data['email_address'];
                $user->save();

                if ($data['role_id'] == 1) {
                    $administrator = new Administrator();
                    $administrator->user_name = $data['name'];
                    $administrator->first_name = $data['first_name'];
                    $administrator->last_name = $data['last_name'];
                    $administrator->phone_number = $data['phone_number'];
                    $administrator->save();
                } elseif ($data['role_id'] == 2) {
                    $customer = new Customer();
                    $customer->user_name = $data['name'];
                    $customer->first_name = $data['first_name'];
                    $customer->last_name = $data['last_name'];
                    $customer->phone_number = $data['phone_number'];
                    $customer->save();
                } elseif ($data['role_id'] == 3) {
                    $lessor = new Lessor();
                    $lessor->user_name = $data['name'];
                    $lessor->first_name = $data['first_name'];
                    $lessor->last_name = $data['last_name'];
                    $lessor->phone_number = $data['phone_number'];
                    $lessor->save();
                }

                $response = [
                    'message' => 'El usuario se ha agregado correctamente.',
                    'status' => 200
                ];
            } else {
                $response = [
                    'message' => 'Error al ingresar los datos.',
                    'status' => 400
                ];
            }
        } else {
            $response = [
                'message' => 'Error al ingresar los datos.',
                'status' => 400
            ];
        }

        return response()->json($response, $response['status']);
    }

    public function destroy(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            $response = [
                'message' => 'El usuario no existe.',
                'status' => 404
            ];
        } else {
            switch ($user->role_id) {
                case 1:
                    $model = Administrator::where('user_name', $user->name)->first();
                    break;
                case 2:
                    $model = Customer::where('user_name', $user->name)->first();
                    break;
                case 3:
                    $model = Lessor::where('user_name', $user->name)->first();
                    break;
                default:
                    $model = null;
                    break;
            }
            if (!$model) {
                $response = [
                    'message' => 'No se pudo encontrar el modelo asociado al usuario.',
                    'status' => 404
                ];
            } else {
                $model->delete();
                $user->delete();

                $response = [
                    'message' => 'Usuario y modelo asociado eliminados exitosamente.',
                    'status' => 200
                ];
            }
        }

        return response()->json($response, $response['status']);
    }
    public function updatePartial(Request $request, $name)
    {

        $user = User::where('name', $name)->first();

        if (!$user) {
            $response = [
                'message' => 'El usuario no existe.',
                'status' => 404
            ];
        } else {
            switch ($user->role_id) {
                case 1:
                    $model = Administrator::where('user_name', $user->name)->first();
                    break;
                case 2:
                    $model = Customer::where('user_name', $user->name)->first();
                    break;
                case 3:
                    $model = Lessor::where('user_name', $user->name)->first();
                    break;
                default:
                    $model = null;
                    break;
            }

            if (!$model) {
                $response = [
                    'message' => 'No se pudo encontrar el modelo asociado al usuario.',
                    'status' => 404
                ];
            } else {
                $data = $request->only(['user_name', 'first_name', 'last_name', 'phone_number', 'email_address']);

                if (empty($data)) {
                    $response = [
                        'message' => 'No se proporcionaron datos para actualizar.',
                        'status' => 400
                    ];
                } else {
                    $model->fill($data);
                    $model->save();

                    $response = [
                        'message' => 'Usuario y modelo asociado actualizados correctamente.',
                        'status' => 200
                    ];
                }
            }
        }
        return response()->json($response, $response['status']);
    }

    public function show($id)
    {
        $data = User::find($id);
        if (is_object($data)) {
            $response = JsonResponses::ok(
                'Datos del usuario',
                $data,
                'user'
            );
        } else {
            $response = JsonResponses::notFound('Recurso no encontrado');
        }
        return $response;
    }

    public function login(Request $request)
    {
        $data_input = $request->input('data', null);
        $data = json_decode($data_input, true);
        $data = array_map('trim', $data);
        $rules = ['name' => 'required', 'password' => 'required'];
        $validation = validator($data, $rules);

        if (!$validation->fails()) {
            $jwtAuth = new JwtAuth();
            $token = $jwtAuth->getToken($data['name'], $data['password']);
            
            if ($token) {
                $response = response()->json($token);
            }
            else {
                $response = JsonResponses::unauthorized('Datos de autenticacion incorrectos');
            }
        }
        else {
            $response = JsonResponses::notAcceptable(
                'Error en la validación de los datos',
                'errors',
                $validation->errors());
        }

        return $response;
    }


    public function getIdentity(Request $request)
    {
        $jwtAuth = new JwtAuth();
        $token = $request->bearerToken();

        if (isset($token)) {
            $response = response()->json($jwtAuth->checkToken($token, true));
        } else {
            $response = JsonResponses::notFound('Token (BearerToken) no encontrado');
        }

        return $response;
    }
}
