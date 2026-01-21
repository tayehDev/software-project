import 'package:flutter/material.dart';
import 'package:flutter_inappwebview/flutter_inappwebview.dart';
import 'package:hexcolor/hexcolor.dart';
import 'package:platform_stores/top_bottom/topBar_static.dart';
import 'package:platform_stores/top_bottom/bottomBar_static.dart';
import 'package:platform_stores/api/config.dart';
//just work in android and ios not work in windows and linux
class MapRouteWebPage extends StatefulWidget {
  final int orderId;
  const MapRouteWebPage({Key? key, required this.orderId}) : super(key: key);

  @override
  State<MapRouteWebPage> createState() => _MapRouteWebPageState();
}

class _MapRouteWebPageState extends State<MapRouteWebPage> {
  bool loading = true;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: topBar(context),
      bottomNavigationBar: footer(context),
      body: Stack(
        children: [
          InAppWebView(
            initialUrlRequest: URLRequest(
              url: WebUri("${config_URL}${config_map}?order_id=${widget.orderId}"),
            ),
            onLoadStop: (controller, url) {
              setState(() => loading = false);
            },
          ),
          if (loading) const Center(child: CircularProgressIndicator()),
        ],
      ),
    );
  }
}
