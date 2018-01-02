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
    { elements : List Element
    , storagePreference : String
    , site : String
    }


type Msg
    = InitData String
    | SendToJs String
    | NoOp


type alias Element =
    { id : Int
    , title : String
    }



-- MODEL


init : ( Model, Cmd Msg )
init =
    ( { elements = []
      , storagePreference = "edit"
      , site = ""
      }
    , Cmd.none
    )



----- UPDATE


port toJs : String -> Cmd msg


port initData : ({ storagePreference : String } -> msg) -> Sub msg


update : Msg -> Model -> ( Model, Cmd Msg )
update msg model =
    case msg of
        InitData prefs ->
            ( { model | storagePreference = prefs }, Cmd.none )

        SendToJs str ->
            ( model, toJs str )

        NoOp ->
            ( model, Cmd.none )



-- VIEW


view : Model -> Html Msg
view model =
    div []
        [ elementList ( model.site, model.storagePreference, (Element 0 "Add a new Fancy element"), FontAwesome.plus (rgb 85 85 85) 14 )
        , div [] (List.map (\element -> elementList ( model.site, model.storagePreference, element, text "" )) model.elements)
        ]


elementList : ( String, String, Element, Html Msg ) -> Html Msg
elementList ( site, storagePreference, element, textPrefix ) =
    a
        [ class "list-group-item", href (createEditUrl ( site, element.id, storagePreference )) ]
        [ textPrefix, text (" " ++ element.title) ]


createEditUrl : ( String, Int, String ) -> String
createEditUrl ( site, id, storagePreference ) =
    storagePreference ++ ".html?site=" ++ site ++ "&id=" ++ toString id



-- SUBSCRIPTIONS
--decodeToString : Value -> Msg
--decodeToString x =
--    let
--        result =
--            Decode.decodeValue Decode.string x
--    in
--        case result of
--            Ok string ->
--                AddElement string
--            Err _ ->
--                NoOp


subscriptions : Model -> Sub Msg
subscriptions model =
    initData (.storagePreference >> InitData)
