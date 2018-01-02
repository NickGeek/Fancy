port module Main exposing (main)

import Html exposing (Html, text, div, button, a)
import Html.Events exposing (onClick, onInput)
import Html.Attributes exposing (type_, value, class, href)
import Json.Encode exposing (Value)
import Json.Decode as Decode
import FontAwesome
import Color as Colour exposing (rgb)


main : Program Never Model Msg
main =
    Html.program
        { init = init
        , update = update
        , view = view
        , subscriptions = subscriptions
        }



-- TYPES


type alias Model =
    List Element


type Msg
    = UpdateStr String
    | SendToJs String
    | NoOp


type alias Element =
    { id : Int
    , title : String
    }



-- MODEL


init : ( Model, Cmd Msg )
init =
    ( [], Cmd.none )



----- UPDATE


port toJs : String -> Cmd msg


port toElm : (Value -> msg) -> Sub msg


update : Msg -> Model -> ( Model, Cmd Msg )
update msg model =
    case msg of
        UpdateStr str ->
            ( Element 1 str :: model, Cmd.none )

        SendToJs str ->
            ( model, toJs str )

        NoOp ->
            ( model, Cmd.none )



-- VIEW


view : Model -> Html Msg
view model =
    div []
        [ elementList ( (Element 0 "Add a new Fancy element"), FontAwesome.plus (rgb 85 85 85) 14 )
        , div [] (List.map (\element -> elementList ( element, text "" )) model)
        ]


elementList : ( Element, Html Msg ) -> Html Msg
elementList ( element, icon ) =
    a
        [ class "list-group-item", href "https://example.com" ]
        [ icon, text (" " ++ element.title) ]



-- SUBSCRIPTIONS


decodeValue : Value -> Msg
decodeValue x =
    let
        result =
            Decode.decodeValue Decode.string x
    in
        case result of
            Ok string ->
                UpdateStr string

            Err _ ->
                NoOp


subscriptions : Model -> Sub Msg
subscriptions model =
    toElm (decodeValue)
