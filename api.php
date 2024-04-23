<?php

use App\Http\Controllers\FichierController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Requests\LoginRequest;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//clément
// Supprimer un fichier
Route::delete('/fichiers/{id}', [FichierController::class, 'supprfile']);

// Renommer un fichier
Route::put('/fichiers/{id}/{NOMFICHIER}', [FichierController::class, 'modif']);

// Récupère un fichier précis
Route::get('/fichiers/{id}', [FichierController::class, 'unfichier']);

// Récupère les infos d'un utilisateur
Route::middleware("auth:sanctum")->get('/users/{id}', [UserController::class, 'profil']);

// Envoie les infos du nouveau compte inscrit
Route::post('/inscription', [UserController::class, 'inscription']);

// Envoie l'avis d'un utilisateur
Route::post('/avis/users/{id}', [UserController::class, 'avis']);


Route::get('/', function (Request $request) {
    return response()->json(["message" => "Bienvenue dans l'API de gestion de fichier"],200);
});

//lucas
//recupère la liste de tous les fichiers de la base de donnée
Route::get('/fichiers', [FichierController::class, 'listfichier']);

//recupère la liste de tous les fichiers d'un utilisateur
Route::get('/users/{id}/fichiers', [FichierController::class, 'listfileusers']);

//modifier un profil
Route::put('/user/{id}', [UserController::class, 'modif_profil']);


// -- gestion des tokens
Route::post('/login', function(LoginRequest $request){
    // -- LoginRequest a verifié que les email et password étaient présents
    // -- il faut maintenant vérifier que les identifiants sont corrects
    $credentials = request(['email','password']);
    if(!Auth::attempt($credentials)) {
        return response()->json([
            'status' => 0,
            'message' => 'Utilisateur inexistant ou identifiants incorreccts'
        ],401);
    }
    // tout est ok, on peut générer le token
    $user = $request->user();
    $tokenResult = $user->createToken('Personal Access Token');
    $token = $tokenResult->plainTextToken;

    $userInfo = [
        'prenom' => $user->prenom,
        'name' => $user->name,
        'email' => $user->email,
        'photoprofil'=>$user->photoprofil

    ];

    return response()->json([
        'status' => 1,
        'accessToken' =>$token,
        'token_type' => 'Bearer',
        'user_id' => $user->id,
        'user' => $userInfo,
    ]);
});

//envoie les infos de contact à la base de donnée
Route::post('/contact', [UserController::class, 'contact']);

//recupère la liste de tous les avis de la base de donnée
Route::get('/avis', [UserController::class, 'listavis']);

//convertir une image et envoie dans la BD
Route::post('/conversionpng/image', [FichierController::class, 'convertirImagePng']);


Route::post('/upload', function (Request $request) {
    $file = $request->file('photo');
    $truc = $request->truc;
    if ($file) {
        $imageName = "categorie" . time() . '.' . $file->extension();
        $imagePath = storage_path() . '/app/public/files';
        $file->move($imagePath, $imageName);
    }
    return response()->json([
            'truc' => $truc,
            'photo' => $imagePath . "/" . $imageName
        ]
    );
});
