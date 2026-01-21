import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:flutter_map/flutter_map.dart';
import 'package:latlong2/latlong.dart';
import 'package:http/http.dart' as http;
import 'package:hexcolor/hexcolor.dart';

import 'package:platform_stores/top_bottom/topBar_static.dart';
import 'package:platform_stores/top_bottom/bottomBar_static.dart';
import 'package:platform_stores/api/config.dart';
import 'package:platform_stores/public/globals.dart' as globals;

final Color backgroundColor = HexColor("#f8f8f8");

class MapRoutePage extends StatefulWidget {
  final int orderId;
  const MapRoutePage({Key? key, required this.orderId}) : super(key: key);

  @override
  State<MapRoutePage> createState() => _MapRoutePageState();
}

class _MapRoutePageState extends State<MapRoutePage> {
  double? sellerLat;
  double? sellerLng;
  double? buyerLat;
  double? buyerLng;
  bool loading = true;

  final MapController mapController = MapController();

  @override
  void initState() {
    super.initState();
    fetchCoordinates();
  }

  /// =======================
  /// 📡 FETCH COORDINATES FROM API
  /// =======================
  Future<void> fetchCoordinates() async {
    final uri = useHttps
        ? Uri.https(config_URL, config_unencodedPath)
        : Uri.http(config_URL, config_unencodedPath);

    try {
      final response = await http.post(
        uri,
        headers: {"Content-Type": "application/json"},
        body: jsonEncode({
          "action": "get_order_coordinates",
          "order_id": widget.orderId,
        }),
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        if (data['data'] != null && data['data'].isNotEmpty) {
          final row = data['data'][0];
          setState(() {
            sellerLat = double.tryParse(row['seller_lat'] ?? "0") ?? 0;
            sellerLng = double.tryParse(row['seller_lng'] ?? "0") ?? 0;
            buyerLat = double.tryParse(row['buyer_lat'] ?? "0") ?? 0;
            buyerLng = double.tryParse(row['buyer_lng'] ?? "0") ?? 0;
            loading = false;

            // مركز الخريطة على البائع
            mapController.move(LatLng(sellerLat!, sellerLng!), 13);
          });
        } else {
          setState(() => loading = false);
        }
      } else {
        setState(() => loading = false);
      }
    } catch (e) {
      setState(() => loading = false);
      print("Error fetching coordinates: $e");
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: topBar(context),
      bottomNavigationBar: footer(context),
      backgroundColor: backgroundColor,
      body: loading
          ? const Center(child: CircularProgressIndicator())
          : (sellerLat != null &&
                  sellerLng != null &&
                  buyerLat != null &&
                  buyerLng != null)
              ? FlutterMap(
                  mapController: mapController,
                  options: MapOptions(
                    center: LatLng(sellerLat!, sellerLng!),
                    zoom: 13,
                  ),
                  children: [
                    TileLayer(
                      urlTemplate:
                          "https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png",
                      subdomains: const ['a', 'b', 'c', 'd'],
                    ),
                    PolylineLayer(
                      polylines: [
                        Polyline(
                          points: [
                            LatLng(sellerLat!, sellerLng!),
                            LatLng(buyerLat!, buyerLng!)
                          ],
                          color: Colors.red,
                          strokeWidth: 4,
                        ),
                      ],
                    ),
                    MarkerLayer(
                      markers: [
                        Marker(
                          point: LatLng(sellerLat!, sellerLng!),
                          width: 40,
                          height: 40,
                          builder: (ctx) => const Icon(
                            Icons.store,
                            color: Colors.blue,
                            size: 40,
                          ),
                        ),
                        Marker(
                          point: LatLng(buyerLat!, buyerLng!),
                          width: 40,
                          height: 40,
                          builder: (ctx) => const Icon(
                            Icons.person,
                            color: Colors.green,
                            size: 40,
                          ),
                        ),
                      ],
                    ),
                  ],
                )
              : const Center(child: Text("Failed to load coordinates")),
    );
  }
}

