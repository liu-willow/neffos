<?php
namespace neffos;


class events
{
    public static string $onNamespaceConnect = "_OnNamespaceConnect";
    public static string $onNamespaceConnected = "_OnNamespaceConnected";
    public static string $onNamespaceDisconnect = "_OnNamespaceDisconnect";
    public static string $onRoomJoin = "_OnRoomJoin";
    public static string $onRoomJoined = "_OnRoomJoined";
    public static string $onRoomLeave = "_OnRoomLeave";
    public static string $onRoomLeft = "_OnRoomLeft";
    public static string $onAnyEvent = "_OnAnyEvent";
    public static string $onNativeMessage = "_OnNativeMessage";
}
