<?php

if ( !defined( 'ABSPATH' ) ):
	exit;
endif;

if ( !class_exists( 'WPExtendedDataToRestAPI_Options_Page' ) ):

	class WPExtendedDataToRestAPI_Options_Page {

		function __construct() {
			add_filter( 'plugin_action_links_' . WPEDTRA_BASE_DIRECTORY, array( $this, 'plugin_action_links' ) );
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			add_action( 'admin_init', array( $this, 'register_settings' ) );
		}

		function plugin_action_links( array $links ) {
			$url = get_admin_url() . "options-general.php?page=wp-extended-data-to-rest-api";
			$settings_link = '<a href="' . $url . '">' . __( 'Settings', 'wp-extended-data-to-rest-api' ) . '</a>';
			$links[] = $settings_link;
			return $links;
		}

		function admin_menu() {
			add_options_page(
					__( 'Extended Data to REST API', 'wp-extended-data-to-rest-api' ), __( 'Extended Data to REST API', 'wp-extended-data-to-rest-api' ), 'manage_options', 'wp-extended-data-to-rest-api', array(
				$this,
				'settings_page'
					)
			);
		}

		function register_settings() {

			add_option( 'wpedtra-meta', 'no' );
			register_setting( 'wpedtra-options-group', 'wpedtra-meta' );

			add_option( 'wpedtra-terms', 'no' );
			register_setting( 'wpedtra-options-group', 'wpedtra-terms' );

			add_option( 'wpedtra-p2p', 'no' );
			register_setting( 'wpedtra-options-group', 'wpedtra-p2p' );


			foreach ( get_post_types( array( 'public' => true, 'show_in_nav_menus' => true ), 'names' ) as $pt ) {
				add_option( 'wpedtra-pt-' . $pt, 'no' );
				register_setting( 'wpedtra-options-group', 'wpedtra-pt-' . $pt );
			}
		}

		function settings_page() {

			echo '<div class="wrap">';
			echo '<h1>' . __( 'Extended Data to REST API Configuration' ) . '</h1>';
			
			echo '<form method="post" action="options.php">';

			echo '<h3>' . __( 'type of data' ) . '</h3>';

			echo '<label for="wpedtra-meta">post meta</label>:&nbsp;';
			echo '<select name="wpedtra-meta" id="wpedtra-meta">';
			echo '<option value="no" ' . (selected( get_option( 'wpedtra-meta' ), 'no' )) . '>no</option>';
			echo '<option value="yes" ' . (selected( get_option( 'wpedtra-meta' ), "yes" )) . '>yes</option>';
			echo '</select><br><br>';

			echo '<label for="wpedtra-terms">taxonomy terms</label>:&nbsp;';
			echo '<select name="wpedtra-terms" id="wpedtra-terms">';
			echo '<option value="no" ' . (selected( get_option( 'wpedtra-terms' ), 'no' )) . '>no</option>';
			echo '<option value="yes" ' . (selected( get_option( 'wpedtra-terms' ), "yes" )) . '>yes</option>';
			echo '</select><br><br>';

			echo '<label for="wpedtra-p2p">post to post</label>:&nbsp;';
			echo '<select name="wpedtra-p2p" id="wpedtra-p2p">';
			echo '<option value="no" ' . (selected( get_option( 'wpedtra-p2p' ), 'no' )) . '>no</option>';
			echo '<option value="yes" ' . (selected( get_option( 'wpedtra-p2p' ), "yes" )) . '>yes</option>';
			echo '</select><br><br>';


			echo '<h3>' . __( 'for which post type?' ) . '</h3>';
			settings_fields( 'wpedtra-options-group' );

			foreach ( get_post_types( array( 'public' => true, 'show_in_nav_menus' => true ), 'names' ) as $pt ):
				echo '<label for="wpedtra-pt-' . $pt . '">' . $pt . '</label>:&nbsp;';
				echo '<select name="wpedtra-pt-' . $pt . '" id="wpedtra-pt-' . $pt . '">';
				echo '<option value="no" ' . (selected( get_option( 'wpedtra-pt-' . $pt ), 'no' )) . '>no</option>';
				echo '<option value="yes" ' . (selected( get_option( 'wpedtra-pt-' . $pt ), "yes" )) . '>yes</option>';
				echo '</select><br><br>';
			endforeach;
			echo '<input type="submit" class="button-primary" id="submit" name="submit" value="' . __( 'Save Changes' ) . '" />';

			echo '</form>';
			
			
			echo '<div style="text-align:center">';
			echo '<h2>make a donation</h2>';
			echo '<hr>';
			echo '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top"><input type="hidden" name="cmd" value="_donations" /><input type="hidden" name="business" value="UHTHLRBY2ZARS" /><input type="hidden" name="currency_code" value="EUR" /><input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" /><img alt="" border="0" src="https://www.paypal.com/en_IT/i/scr/pixel.gif" width="1" height="1" /></form>';
			echo '<br>';
			echo '<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAQAAAAEACAYAAABccqhmAAAY+0lEQVR4Xu2d3XYbOQyDm/d/6O5Jdt2up1YgfAMpMw16qx+SIAhRsp2+/fjx4+ePG//7+ZO5//b2tiRq1x/lh7vfIyi1rxv8Kj/cfWlcrp1ZfKg/s/uvnvdeBayCVns2uT9N7KrEuf4oP9z9KgCviUNxVDRU+VPrv3q8AhDOgEs0RSB3vwpABcChdAXAQWtirluwFQCvAVV4jVLk5mUi1R9TqD+z+6+eVwEII+wSTRHI3a8dQDsAh9IVAAetibluwVYA2gFM0GrZlKEAuERe5uF/G48KZbefqmDTOIziW+UHxZP6Q+2NcKY82b0uzRO13yi+CoBC7jBOiW6a+TW9AuAht7uQqT0vqvOzKwDnMfySR58KgJc4WpC713lRnZ9dATiPYQXgEwxpZ9QrQIiYYpsKQAhnSnRqvh2Ah9zuk5za86I6P7sCcB7DdgDtAP5AoAIQKiy1zVWAbgfwOlMUl14BFPMz4+0AMjhu/+ZXrwBe4uhBsXudF9X52XEBoIo/CkWdBDRBI3tp/x92VBxHf5Qf7n6KKsre7vzsyqvCkfqRXqfyl84P/h4AJVI6AJXYCsAzAjRvCud0ISh7bl7Vfrv9p3lI108FgEruYJ0iWjuAOUFycXzsepVCVv5XAA6VQAFT69yT4qweuP4oIrj7Kf+VvfQJo/ynBevmdZUf1H+ah3R+2gGoijHHFdHaAbQDeEegAtAO4AMBRQRXUJReKXvpE0b5T0/QdgDPCFCc//oOgBKMFooqwOO4SpxL9Md8uq/r/6q7N/Wjcb9G7tt+DFgBoKXkrbsKzhWACsATAlchJm2t2wF4QlQBqABUAN7/1DP8c+leuf2efRWhbdwVgApABYDqmL3uLsLXN4DQpw42Q8QCemKpR0q6L43vLoVA43OvYgp/lT/XT2qvnwIMkE4nqG8AXmvqFkA//fj8j6u2A2gHQGtqal07gGeY6Ik8BfaLSdReOwCzA6BAq3Vu4mmHQv2g9ty4/paTXOGcxpPaqwBUAKZqNE1YZVQRWq13x9MdjPI/jSe1VwGoAEzVSpqwyqgitFrvjlcAvviOrBJ+lQSt8jNFWLWP8t997Vb26Dj1k9q7Cr+o/wqvPgKGBI4Crda5iacnMvWD2nPj6hsAQ0zltQJQAfhAQBGlHcAzAgqvdOfAyl/nNS4A1FG6jgKdJnSaEOpkHdnbvU7lLZ0fmjeVH3dftd+uuBX+arwCcEBIFdAI0DQhlB8VgOdMULxogaTzrfxYNV4BqAB8IHC1AnIJfzX/2wG4GYTz00ArIrUD8BKVzo/bqp99PKT+03UeuudntwNoB9AO4JM6ooVM150vaW+HCkAFoAJQAfgDAfxNQE9/zs9OK22vAK9zoh693Nac7ufa6RXg8xprB9AOoB1AO4D5DuD8mb1nB3XC0M5h1zrViVzlY8Cr+HmXfO9h/3krwyvA+a337HAXQrSQvSvHLgF+eJW2t4f9561UAAYYpglRAagAnC/X/A4VgArAEwJXF6q7dHz5Ul2zYwWgAlAB+OQbklRw1pRrftcKQAWgAvCdBeCnkri86Fxix1Wv2qPgKMzKz7S90X7Kj11Xh7vjeAny/8+JtwpA5nFKJfbuxK0AqAzfc7wCMMgbPdHSJ7IqvLS9dgDPCFDhvoscVAAqAFNcVUJEBdNdRwtS+b9LSKfA3jipAlABmKKbKiC3kB9G3XUVgKl0TU+qAFQApshSAZiC6XaTKgAVgCnSVgCmYLrdpApABWCKtBWAKZhuNwl/EYjexegrM0XWvWMqOzRuVUDuIxTdT8Xn+kH3e6zbFQfN29n40nxPx1EBMDNME0CJnhYwM9xf02ncyh7FRe17HF/lv+vHWeFLx1EBMDNIE0CJXgEwE2Re6TK7+7uk+eB78O+KCoCJXAXABExMp4XgekHz5tqZnU/jTsdRAZjN2H/zaALSCaf7meH2CkABWyR8lH/Dt4j3/y6OxBh35O1di/L/0i00jZsWbNp/ijCNW9mjuKh9j+Or/Hf9eMyncafjiH8MSANTQNLAV/kz8tf1U/nn7qdw3D2u4kvhqOK6ih9KAGi+VXzDgyT9a0DliEpUmhCr/En5qfyjhKA4p9ep+FI4Kr+v4kcFQGVqME4LgSYeumn/99vKPxo39T+9TsVXAXhGgOZb4dwOIM3skFDRxG0K57QZFV8FoAIwRbJVyjhl3Jjk+qkKxN3PcHXLVBVfBaACMEVEWgiUgFNOvZjk+qn8c/ejfq9ap+KrAFQAprhHC4EScMqpCoCEieJP8z1y6Cp+PPwb+UPjVvH1DUBSNTPBTSBNXMbb9buo+NoBXLQDUImjX1Ch69JEUfGl7aVPplWl6wqY8kPhvIsPq/zYjRfl5Sj+4ReBdgOm7NHA04V3lYSrwqPju+OrADxnalUdVAAOFbEKaLfwqB+undn5FQCvIHfjRQ/CCkAFYEoDdhO6HYAnOBWAAwKUsPTkpfbSV5GpagaTdsdXAagAfCCwuyB326sAvEagAvDFArD758C7P/+kLRM4PD8VMkr0q5zIFI/d66iwp3lC/aD5VvaG/KsAZCnqChxNHPV6tz3qJ12n4nP3XVWQuwWnAhB6O1AEqgAohNaOVwDMq1Y7gCwhKwBZPN3dKgAVgCnO0NZObV4BUAitHa8AVACmGFYB8IgyBeoFJlUAvLxu/6vA7gmpOEUTXgHwiKLycJVxyofdj3K77fURsI+AHwioAlkljLsEQsXn+kHxoH6ssmf/HFgFQB11E/CYTzsHFYerxGo/F5f0fmfxGuFxFz+/K79UfoZ5Hf1VYLWhS3SamLOEVnFUAOYyo3CkfKDCPuf1/Czqh8JlF7+oH/GfA89D7s28e4LufrIqglUAPD6nvxmq8tMOwMvP8M97K6DdQkjvd7ZjurtQmWn+Nf3uB4ziUQXAZEZaoe9eWIpgrvCtEiozzRWAvgG8pkwF4BmXCsBrnihcLv8GcJWvAivlTp8wyh4dp8JB7bnrVuGY3pfuRzstt1AV7koYdvPk8t8DUIBSQqhEKLvu+O7EpvxT+ygc0/mh+1UAzI62HYCivjdeAfDwoo9vnhX9Bah2ACaiVKHVSbI7EWbYcnoFQEL0NKEC4L21eOj+nt0rAEXOXFcB8ACrAFQAphizu+OYcurFpAqAh1wF4IsFYPQxoJfG+dn0CjBv4XkmFQ5qz123Gw/l393xSguwwoPmT+2bfswc7lcBUCWxdpwSaJVXlJir/Dnuq/CqAHiZGP4WwNtmfrZK4PxOczPvTui5KHOz7o5XBcDjQgXAwys+e7cgqgAqAN4VkuaP4kzt9QqgmP9F4+mEng2DEvOs3dn1Cq92ALNI/juvHYCHV3y2InTcoNiwAtAOYCnndhP+7oRemowXm98dr3YAHmPaAXh4xWfvFkQVQAXgm3UAo98CXJ0IjzSpAtp9IqgCc8dVfKP9aNzpvCv/XT/T/s3yyMVZ5Zl+ASq9bvhnwVcBrYBxx9MEU/Z346Lic4mp9kvHR+1Roqv8jcaVny7Oyg8aX3pdBUBl6jCeLhBlPk1MtV86PmqPEl3hWQF4RqACYDImXSDKvCog92RS+6Xjo/YqAK8zS3EZrasAqApsB2AidDhh3t4pNv7XN4BnbJQAVwAOXEqfMIrtKkFqvTuu4msH4CLqnaxqd8qHdCErP4YdwO4fA139DqYSrsZVIo7r0wWu/KPjyk/3JH/4Qde5cbh5eeyv4nYF+Oy+aXvbvwdQAfBaZJpwt0DUfFUItJDpOuXvcbwC8PoqVgFwmSTmu0RThVUByCTIzcvZk1rZo3mnfOgV4IBcOgGqpb1K50PLSeFFT3K6zo1DFeTu/Cg80/FVACoALqee5ivC0kKm69xgKgC9AliEdgnWDuDnS8hWCYebnwpABaAC4FbN/+avKuR2ACeS8r+lSuB6BegV4BTTKgAefLQgPSu/Z1N7w08BVMLpayQNcPcjzd9qT+WVnsh0XZpHKj6Xf6qwKE/SeKm4hvbo/w6cTpwKgAKd9pMSbBWR3PiU/5SYdJ3rv+KJik+tP46vylsaLxVXBeCA0KrEXoXQlGC7110Fr91+UJxVobtx9ApgIkpPmKsIjvKfEpOucwmr0qXiU+vbAfyHAAWSEt1NzGP+bj/vbk/5TwuZrqsAPCOg8kPrpFeAXgE+EFAEo4VM11UAvlgA0n8TUBGMJjy9r9qPEtpdpzom5Sc9EXatS8eX3k/hoOyN1tO8UXsqjuH3ACoAr6FzC/mxi7tOJZwSSRFi13g6vvR+CgdlrwJwQIASVgGd3lft5xZyBcATUvqGs4ontDOtAFQAnhBwhWM3odWJlx5Px5feT8Wr7FUAKgAVgE+qSBWQ6sSOW6f3qwD8+PHyZ1wK6N3K5xKFtuS71ymcadyK2LvG0/Gl91M4KHu760D56/oT/6vAlLAK6PS+aj+3lafCsSpuSpT0unR86f1UvMqeW3Cr7Kl9+ymAeVWpAChKzY2rAlJC3CvAHM5q1lAA0j8GUo6MxhVR6L6jwFfZc/10C2B2/7vHl/Zf4ZwW/Nk8HedRP5W9CsABoTTBVALSraKyd/f40v7TwqLrVH4oHyguFYAKAOXkqXWqgHZ1hMqPdgDmHfkUK14spsqm/OgVQCG0dlwVXgXgGQGFF62TdgDtANZW+mB3RegKQAXgAwGqbIrV7QAUQmvHKwAevgovWid2B6DcpoWVXqf8pCdM2s+r3DEVXjRu+qil/HHH0zirgnT9U/NVgaf9wf81GCVKep0CtALgIUTzUwHwcE7zklqvAJh3V6rQ6ZOJJlytqwB4d3KFpztO+eXaecyvAFQAnhCoAFQApsSEEiW9bsrZF5Oo0qbXqTudskfjd1t26oeKL+1/utO6iv+/Tuy31//FF8WxHUA7gHYAn3wKVQH44gJZpXzqREt3KumTiSq+WkfjdjsK5QcdT+NcAagAPCGQFg5FMGWPFopbsNQPFV/a/wqAh+j2K4BLvMd8mlgPjvOzaaG4uKTtnI+c7eB2HEpQKE/SeCo/GVp81RCX0c+BlSk3cXS/CsDrR580YVV+Vo27PFKFVQF4nakKwCoGH/ZNF6ZbIJvCjJlx46sAMOgrAAw3e1UFwIOsAuDhRWdXAChy5roKgAdYBcDDi86uAFDkzHUVAA+wCoCHF509FIDRfw2mDFGiqzucspsap/6n7D/2uQoeZx9bd+H53fFKxz/8s+CK6DTh6QCUn6Nx6j+1N1p3FTwqACyzuz91SPOlAsDyHluVTuhZx3YT2vX3u+OVjr8C4DIwPD+d0LPuVQA8BHfjleZLBcDLd3x2OqFnHdxNaNff745XOv4KgMvA8Px0Qs+6VwHwENyNV5ovFQAv3/HZ6YSedXA3oV1/vzte6fgrAC4Dw/PTCT3rXgXAQ3A3Xmm+4F8D0o+10h+/KUCoPbWvR5Pxnz1XdtIEu5q99Me07heLHvYVLql8n7WX5nMFYJDZXYRQdioAXulVAF7jNcKlAlABeEJgt+C0A/AErh3AAS96girY1b5q/XF8d2HdxV4FwGNSBaAC8IGAEqgKwDNRVOEoPL0y1f/VHbWn4nDf5noF6BWgV4AJQf1rBYD+GtAF5DGfnky7WsVVflL/6UmRtkdPHsoT+piXtkf3242X8nP4CFgBeA1dWqjSBakSnra3m9AVAJrh1+sqAKG3g6ucyJQeqpB3F557Z1X+U1x255X6SddVACoAHwioAqoA0BLzHh0zVuZ3qQBUACoAn9RLO4B5MTk1M323Viea22L2EfAa/w/B7k6kAnCqrNcvpoVOPaOEcP2kdlRcrh9qP+qn8mNXoSv/lZ8Kn+M4tbd93e5PAVwg1YlM91PrVCJG610iUTvKf9cPtR/1U/lRAXhGXuFMO+jhugrAa+qrRFQAlGT8O14BeI1TvJDfXl/d1AGK/x7AXPpzsxSRcpb+3akC4J1MVBDbAXg4x4WjHUA7gBnxXCWIFYAKwAz/ZCs5tYkxaRXh3cciw+WnqemOaRUeFYAKwBTH04RWRlcRvgIwR/h0vlU+r2KP+onX9QrQK4ASw5VvIu0A5gRRPualHwGVIirFmSHVjjkqjrQPLi7KP1ogrh9nCUYfAUfrruK/4kf6UU7Zo7gMcR51AJSYKoDd4yqOtD9ugpR/FQAvQ7QgPSu/Z1N7Ku9pYawA0Ayb6yoAz4DtJjotSDPNv6ZTe7txqQDQDJvrKgAVgJk3kwqAWVh0OgWa2qsAVAAqALR6FqyrAHig0pa2j4DPCKiDgPJS7etl+8eP4VeBlYNpR5Tjyh/30SS9H/V/FY40PhXH3zpO80Bxpvao0I7sVQBMRtPEpU9W5TYlptr3bx1P51XhRO1VABSyh3H6MZrbUSi3KgAKoa8dpwVJhZbaqwCYPKkAmIB90+m0ICsAmwiTBjq9n4KhHYBC6GvHKwAH/FWBUMBompU/bsue3k/FVQFQCH3tOOXzbh71CmDypFcAE7BvOr0C0A5givppotD9lLP0ZFL7/q3jNA8UZ2rv9h3AVQBTRKZ+pq8iaaKouOk4veLQda6fKp9XwZn6qdYNebn714DYUfF7Z5cQaj71swLwjIAqrArAM16Kd/ErbQXgdcmqRCgBOY7TxKkCcv1YNZ8WMl3nxqHyeRWcqZ9qXTsAkzEU0HYA7QBMqj1NV7yjB0kFwMyKSoS53fDPjCs7VzmZVLz0JKfrlD/H8bvgTP1U6yoAJmMooO0A2gGYVPvaDuBnmulnol+wdvcJmoaT+p8+WVVctDV11yk8lJ9pgaaUVXGM9qXxDeOuANAUfu3jofK6AqAQmutU0gX3sFoB8PKDZ1OgqcE0Yaj/FQAvg24n4u3+5+x0Xqk/b+0AKHTtAP6PAC0gd50qHCrArh9nWaPi6BXgLML/radAU/OUgO7dVPnXDkAh1CvAOwLtADyeyNkVgGeIFB7uyasEXdlzhZbup4ii4mgHoBCcHKdAT27/x7Q0Yaj/7QC8DLpC5O3eN4CzeOH1tICowQpAO4AZ7lBexvl1l/8clLZEFOiZJL6aE08Q/BEU9eMqJyHtYHbHTfGi6ygvR/aGfxWYGtq9TiW8AuBl5CrErAB4eVOzKwAKodC4EiTXDBUw6kcFYO0V5rH7VXBuB+BWpJhPC899nVZuUz+uQsx2ACrD3ng7AA8vPJsWXgVg7uRVHRHFnwrf7nWUmBUAipy5jhKwAlABeEcgzR915cD/NZhZF6en30VpTwd62IDGfRVBUXhcJb6Rn7sLktqjnVEFQDH0i8evUiCUmAq+q8RXATggsCrhihDuyaX8TBOM+k/Xpf1XJ8XfXghuHhS/3P1US07tqbwOH1XpHwWlgdN1tBDoOupnel3af0WUCsDcW8TZPO/OawXg4h1OuvNx91OEpieT2nd3ISh/juN/S9wVgArABwLtADwJqAB4eC2bTU8Kum5ZIObGaf8rAF4CKgAeXstm00Kg65YFYm6c9r8C4CWgAjDAixKJPjLdvRDSeHk0/j2bElr5T7+66/JB+UFx+a7r8PcA0olQxKwAZCiqcKaPhxWATH5271IBMBFfVUCmG3j6Kv8rADglX7qwAmDCv6qATDfw9FX+VwBwSr50YQXAhH9VAZlu4Omr/K8A4JR86cIKgAn/qgIy3cDTV/lfAcAp+dKFFYAB/JTQtMDcxzdqhz7errJH93XxUlVG8+1+iqH8UPmJ40V/C6AcVYEex1Vguz8FoIRQcbi47I779oQO/xFVynPKA2WP7jsUzArAa2gqAM+4UOJtJ3QFwDpjegXoFWCKMBWAKZh+TboNXu0A2gHMUPs2hG4HMJPOX3PaAbQDmCJMBWAKpnYAHky/ZyuC7X4M6xtA3wDeEVBvGLd/NO0VoFeAGdFWAk0/lqP7UntuwVYABohRYNwEPOZfpQOYKZZXc6j/aZyV/7TzUfvSvLv7UpxdO2q+yttunIeC2Q7A6wBU4t2TSZ2AikjUH7cgV/mh4nfjqwB4iPURcIDXVYi5qvAqAF6huLNV3toBHBBVBZdWdpoglwhnrzDKT+pPBSCN3PN+Km8VgArABwJU+FbRdzcxVfxunOmDwrWvBP8xvhvnvgEcEKAKnSaEKgDlJ/WnHUAauW/WAayF78/dqbLTAqIKrQr6GJnyb5cf6uRy41L7qZPw7vy6Gl4jnuFHwLsnSPm/q/AqAD9VKraMpw+YCkA4bekEKfcqAM8IXY3QKn/ueJpfV8OrHYDJiApABeDMI20FwCw4NT2t0MpeBaACUAFQVbJxvAKQeWVWKaM4j/albxzKz/Q4jZuu241XrwAmY9oBtANoB2AWzcrpVGnVCTTyuQJQAfjWArCymJN7q8eWtHCssue2hMoPijHFi9pLr6P+03Vu3lS8u/M6/B6AcvQq4wowmtjd61wiqbhpfmjc1F56HfWfrnPzpuLdndcKwCAjlBB0nUuk3URZZU8VhDtO8afr3LypeFbhbD8CKkevMq4Ao4ndvc4lkoqb5ofGTe2l11H/6To3byre3XltB9AO4AmBdCEowqfHqf90XQUgnUFzP6WYNLG717lEUnGbMP6aTuOm9tLrqP90nZs3Fe/uvP4DtsucoxzH+hQAAAAASUVORK5CYII=" />';
			echo '<hr>';			
			echo '</div>';			
			
			echo '</div>';
		}

	}
	
	


	

	

	

	

	

	

	

	

	

	
endif;
